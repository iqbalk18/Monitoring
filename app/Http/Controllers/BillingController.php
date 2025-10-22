<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BillingController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user  = session('user_name');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/login')->withErrors(['login' => 'Please Login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate   = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $statusFilter = $request->query('status', 'failed','ready to rerun','reversed');
        $typeFilter   = $request->query('type', 'FinalBilling');
        $page         = (int) $request->query('page', 1);

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                    'limit'             => 500, 
                    'salesOrganization' => $org,
                    'page'              => $page,
                    'fromDate'          => $fromDate,
                    'toDate'            => $toDate,
                    'status'            => $statusFilter,
                    'includeDetails'    => 1,
                    'type'              => $typeFilter,
                ]);
        } catch (ConnectionException $e) {
            return view('billing', [
                'token'               => $token,
                'user'                => $user,
                'org'                 => $org,
                'recaps'              => collect([]),
                'error'               => 'Request timeout, Unstable Connection',
                'currentPage'         => $page,
                'lastPage'            => 1,
                'statusFilter'        => $statusFilter,
                'fromDate'            => $fromDateRaw,
                'toDate'              => $toDateRaw,
                'totalFinalAmount'    => 0,
                'totalAmount'         => 0,
                'totalAmountFree'     => 0,
                'totalDepositAmount'  => 0,
                'recapCount'          => 0,
                'totalData'           => 0,
                'typeFilter'          => $typeFilter,
            ]);
        }

        if ($response->successful()) {
            $data = $response->json();
            $recapsRaw = collect($data['data'] ?? []);

            if ($statusFilter === 'failed') {
                $recaps = $recapsRaw->filter(function ($recap) {
                    return !is_null($recap['sapErrorMessage']) ||
                        collect($recap['items'])->contains(fn($item) => !is_null($item['sapErrorMessage']));
                })->values();
            } else {
                $recaps = $recapsRaw->values();
            }

            $recapCount = $recaps->count();

            $totalFinalAmount = isset($data['additional']['totalFinalAmount'])
                ? (int) $data['additional']['totalFinalAmount']
                : (int) $recaps->sum('totalFinalAmount');

            $totalAmount = isset($data['additional']['totalAmount'])
                ? (int) $data['additional']['totalAmount']
                : (int) $recaps->sum('totalAmount');

            $totalAmountFree = isset($data['additional']['totalAmountFree'])
                ? (int) $data['additional']['totalAmountFree']
                : (int) $recaps->sum('totalAmountFree');

            $totalDepositAmount = isset($data['additional']['totalDepositAmount'])
                ? (int) $data['additional']['totalDepositAmount']
                : (int) ($data['additional']['totalDepositAmount'] ?? 0);

            $totalData = $data['total'] ?? $recapsRaw->count();

            $currentPage = $data['current_page'] ?? $page;
            $lastPage = $data['last_page'] ?? (int) ceil(($totalData ?: $recapsRaw->count()) / 5);

            return view('billing', [
                'token'               => $token,
                'user'                => $user,
                'org'                 => $org,
                'recaps'              => $recaps,
                'currentPage'         => $currentPage,
                'lastPage'            => $lastPage,
                'statusFilter'        => $statusFilter,
                'fromDate'            => $fromDateRaw,
                'toDate'              => $toDateRaw,
                'totalFinalAmount'    => $totalFinalAmount,
                'totalAmount'         => $totalAmount,
                'totalAmountFree'     => $totalAmountFree,
                'totalDepositAmount'  => $totalDepositAmount,
                'recapCount'          => $recapCount,
                'totalData'           => $totalData,
                'typeFilter'          => $typeFilter,
            ]);
        }

        return view('billing', [
            'token'               => $token,
            'user'                => $user,
            'org'                 => $org,
            'recaps'              => collect([]),
            'error'               => 'Failed get data: ' . $response->body(),
            'currentPage'         => $page,
            'lastPage'            => 1,
            'statusFilter'        => $statusFilter,
            'fromDate'            => $fromDateRaw,
            'toDate'              => $toDateRaw,
            'totalFinalAmount'    => 0,
            'totalAmount'         => 0,
            'totalAmountFree'     => 0,
            'totalDepositAmount'  => 0,
            'recapCount'          => 0,
            'totalData'           => 0,
            'typeFilter'          => $typeFilter,
        ]);
    }


    public function exportExcel(Request $request)
    {
        $token = session('token');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/login')->withErrors(['login' => 'Please Login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate   = \Carbon\Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid date format']);
        }

        $statusFilter = $request->query('status', 'failed');
        $typeFilter   = $request->query('type', 'FinalBilling');

        $allRecaps = collect();
        $page = 1;
        $hasMore = true;

        try {
            while ($hasMore) {
                $response = \Illuminate\Support\Facades\Http::withToken($token)
                    ->timeout(60)
                    ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                        'limit'             => 500,
                        'salesOrganization' => $org,
                        'fromDate'          => $fromDate,
                        'toDate'            => $toDate,
                        'status'            => $statusFilter,
                        'includeDetails'    => 1,
                        'type'              => $typeFilter,
                        'page'              => $page,
                    ]);

                if (!$response->successful()) {
                    break;
                }

                $data = $response->json();
                $recaps = collect($data['data'] ?? []);
                $allRecaps = $allRecaps->merge($recaps);

                // cek apakah masih ada halaman berikutnya
                $currentPage = $data['current_page'] ?? $page;
                $lastPage = $data['last_page'] ?? $page;
                $hasMore = $currentPage < $lastPage;

                $page++;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return back()->withErrors(['export' => 'Request timeout or unstable connection']);
        }

        if ($allRecaps->isEmpty()) {
            return back()->withErrors(['export' => 'No data available for export']);
        }

        // Buat spreadsheet
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Billing Recap');

        // Header Excel
        $headers = ['No', 'Recap Code', 'Ref ID', 'Status', 'Final Amount', 'SAP Error Message'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        // Isi data
        $row = 2;
        $no = 1;

        foreach ($allRecaps as $recap) {
            $recapCode = $recap['recapCode'] ?? '-';
            $status = $recap['status'] ?? '-';
            $finalAmount = $recap['totalFinalAmount'] ?? 0;
            $sapError = $recap['sapErrorMessage'] ?? '';

            // Ambil refIds dari items (seperti di view)
            $refIds = collect($recap['items'] ?? [])
                ->flatMap(fn($item) => collect($item['belongsToRefs'] ?? [])->pluck('refId'))
                ->filter()
                ->unique()
                ->values();

            if ($refIds->isNotEmpty()) {
                foreach ($refIds as $refId) {
                    $sheet->setCellValue("A{$row}", $no++);
                    $sheet->setCellValue("B{$row}", $recapCode);
                    $sheet->setCellValue("C{$row}", $refId);
                    $sheet->setCellValue("D{$row}", $status);
                    $sheet->setCellValue("E{$row}", $finalAmount);
                    $sheet->setCellValue("F{$row}", $sapError);
                    $row++;
                }
            } else {
                $sheet->setCellValue("A{$row}", $no++);
                $sheet->setCellValue("B{$row}", $recapCode);
                $sheet->setCellValue("C{$row}", '-');
                $sheet->setCellValue("D{$row}", $status);
                $sheet->setCellValue("E{$row}", $finalAmount);
                $sheet->setCellValue("F{$row}", $sapError);
                $row++;
            }
        }

        // Auto-size kolom
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // File name
        $fileName = 'Billing_Recap_' . now()->format('Ymd_His') . '.xlsx';

        // Stream response
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
            $writer->save('php://output');
        });

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}
