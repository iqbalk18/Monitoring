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
        $user = session('user_name');
        $org = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $statusOptions = ['success', 'failed', 'ready to rerun', 'reversed'];
        $status = $request->query('status', 'success', 'failed', 'ready to rerun');
        if (!is_array($status))
            $status = [$status];

        $typeFilter = $request->query('type', 'FinalBilling');
        $page = (int) $request->query('page', 1);

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                        'limit' => 500,
                        'salesOrganization' => $org,
                        'page' => $page,
                        'fromDate' => $fromDate,
                        'toDate' => $toDate,
                        'status' => count($status) === count($statusOptions) ? null : implode(',', $status),
                        'includeDetails' => 1,
                        'type' => $typeFilter,
                    ]);
        } catch (ConnectionException $e) {
            return view('billing', [
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => collect([]),
                'error' => 'Request timeout, Unstable Connection',
                'currentPage' => $page,
                'lastPage' => 1,
                'status' => $status,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
                'totalFinalAmount' => 0,
                'totalAmount' => 0,
                'totalAmountFree' => 0,
                'totalDepositAmount' => 0,
                'recapCount' => 0,
                'totalData' => 0,
                'typeFilter' => $typeFilter,
            ]);
        }

        if ($response->successful()) {
            $data = $response->json();
            $recapsRaw = collect($data['data'] ?? []);

            if ($status === 'failed') {
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
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => $recaps,
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'status' => $status,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
                'totalFinalAmount' => $totalFinalAmount,
                'totalAmount' => $totalAmount,
                'totalAmountFree' => $totalAmountFree,
                'totalDepositAmount' => $totalDepositAmount,
                'recapCount' => $recapCount,
                'totalData' => $totalData,
                'typeFilter' => $typeFilter,
            ]);
        }

        return view('billing', [
            'token' => $token,
            'user' => $user,
            'org' => $org,
            'recaps' => collect([]),
            'error' => 'Failed get data: ' . $response->body(),
            'currentPage' => $page,
            'lastPage' => 1,
            'status' => $status,
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
            'totalFinalAmount' => 0,
            'totalAmount' => 0,
            'totalAmountFree' => 0,
            'totalDepositAmount' => 0,
            'recapCount' => 0,
            'totalData' => 0,
            'typeFilter' => $typeFilter,
        ]);
    }


    public function exportExcel(Request $request)
    {
        $token = session('token');
        $org = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid date format']);
        }

        $status = $request->query('status', []);
        $typeFilter = $request->query('type', 'FinalBilling');

        if (!is_array($status))
            $status = [$status];

        $allRecaps = [];
        $page = 1;
        $hasMore = true;

        // Increase execution time for large exports
        set_time_limit(600);

        try {
            while ($hasMore) {
                $response = Http::withToken($token)
                    ->timeout(120)
                    ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                            'limit' => 500,
                            'salesOrganization' => $org,
                            'fromDate' => $fromDate,
                            'toDate' => $toDate,
                            'status' => count($status) ? implode(',', $status) : null,
                            'includeDetails' => 1,
                            'type' => $typeFilter,
                            'page' => $page,
                        ]);

                if (!$response->successful())
                    break;

                $data = $response->json();
                $pageData = $data['data'] ?? [];

                // Append data to array instead of merging collections for performance
                foreach ($pageData as $item) {
                    $allRecaps[] = $item;
                }

                $currentPage = $data['current_page'] ?? $page;
                $lastPage = $data['last_page'] ?? $page;
                $hasMore = $currentPage < $lastPage;
                $page++;
            }
        } catch (ConnectionException $e) {
            return back()->withErrors(['export' => 'Request timeout or unstable connection']);
        }

        if (empty($allRecaps)) {
            return back()->withErrors(['export' => 'No data available for export']);
        }

        // === CREATE EXCEL ===
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Billing Recap');

        $headers = ['No', 'Recap Code', 'Ref ID', 'Status', 'Final Amount', 'SAP Error Message'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:F1')->getFont()->setBold(true);

        $row = 2;
        $no = 1;

        foreach ($allRecaps as $recap) {
            $recapCode = $recap['recapCode'] ?? '-';
            $status = $recap['status'] ?? '-';
            $finalAmount = $recap['totalFinalAmount'] ?? 0;
            $sapError = $recap['sapErrorMessage'] ?? '';

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

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Billing_Recap_' . now()->format('Ymd_His') . '.xlsx';

        // === STREAM RESPONSE ===
        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
