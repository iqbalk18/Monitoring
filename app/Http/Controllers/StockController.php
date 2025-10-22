<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Carbon\Carbon;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;


class StockController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user  = session('user_name');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/login')->withErrors(['login' => 'Please login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate   = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $typeOptions = ['StockConsumption', 'StockReturn'];
        $statusOptions = ['success','failed','ready to rerun','reversed'];

        $type = $request->query('type', $typeOptions);
        if (!is_array($type)) $type = [$type];

        $status = $request->query('status', $statusOptions);
        if (!is_array($status)) $status = [$status];

        $page = (int) $request->query('page', 1);

        try {
            $response = Http::withToken($token)
                ->timeout(120)
                ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                    'limit'             => 100,
                    'salesOrganization' => $org,
                    'includeDetails'    => 1,
                    'type'              => count($type) === count($typeOptions) ? null : implode(',', $type),
                    'status'            => count($status) === count($statusOptions) ? null : implode(',', $status),
                    'fromDate'          => $fromDate,
                    'toDate'            => $toDate,
                    'page'              => $page,
                ]);
        } catch (ConnectionException $e) {
            return view('stock', [
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => collect([]),
                'error' => 'Request timeout, unstable connection',
                'currentPage' => $page,
                // 'lastPage' => 1,
                'total' => 0,
                'type' => $type,
                'status' => $status,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
            ]);
        }

        if (!$response->successful()) {
            return view('stock', [
                'user' => $user,
                'org' => $org,
                'recaps' => collect([]),
                'error' => 'Failed to get data: ' . $response->body(),
                'currentPage' => $page,
                'lastPage' => 1,
                'total' => 0,
                'type' => $type,
                'status' => $status,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
            ]);
        }

        if ($response->status() === 401) {
            Session::flush(); 
            return redirect('/login')->withErrors(['login' => 'Session expired. Please login again.']);
        }

        if (!$response->successful()) {
            return view('stock', [
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => [],
                'error' => 'Failed get data: ' . $response->body(),
                'currentPage' => $page,
                'status' => $status,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
                'total' => 0,
                'recapCount' => 0,
                'type' => $type,
            ]);
        }



        $data = $response->json();
        $recapsRaw = collect($data['data'] ?? []);

        if ($status === 'failed') {
            $recaps = $recapsRaw->filter(function ($recap) {
                return !is_null($recap['sapErrorMessage']) ||
                    collect($recap['items'])->contains(fn($item) => !is_null($item['sapErrorMessage']));
            })->values();
        } else {
            $recaps = $recapsRaw;
        }

        $recapCount = $recaps->sum(fn($recap) => count($recap['items']));
        $total = $data['additional']['total'] ?? 0;


        return view('stock', [
            'token' => $token,
            'user' => $user,
            'org' => $org,
            'recaps' => collect($data['data'] ?? []),
            'currentPage' => $data['current_page'] ?? $page,
            // 'lastPage' => $data['last_page'] ?? 1,
            'total' => $total,
            'type' => $type,
            'status' => $status,
            'recapCount' => $recapCount,
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
        ]);
    }
    public function exportExcel(Request $request)
{
    $token = session('token');
    $org   = session('sales_org'); // pastikan sesuai nama session yang kamu pakai

    if (!$token) {
        return redirect('/login')->withErrors(['login' => 'Session expired. Please login again.']);
    }

    $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
    $toDateRaw   = $request->query('toDate', now()->format('Y-m-d'));

    try {
        $fromDate = \Carbon\Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
        $toDate   = \Carbon\Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
    } catch (\Exception $e) {
        return back()->withErrors(['date' => 'Invalid date format']);
    }

    // Terima status & type baik sebagai string atau array
    $statusFilter = $request->query('status', []);
    $typeFilter   = $request->query('type', []);

    if (!is_array($statusFilter)) $statusFilter = [$statusFilter];
    if (!is_array($typeFilter)) $typeFilter = [$typeFilter];

    $statusParam = implode(',', array_filter($statusFilter, fn($v) => $v !== null && $v !== ''));
    $typeParam   = implode(',', array_filter($typeFilter, fn($v) => $v !== null && $v !== ''));

    $allRecaps = collect();
    $page = 1;
    $hasMore = true;

    try {
        while ($hasMore) {
            $response = \Illuminate\Support\Facades\Http::withToken($token)
                ->timeout(120)
                ->get('https://cerebro.ihc.id/api/sap/monitoring/recap', [
                    'limit'             => 500,
                    'salesOrganization' => $org,
                    'fromDate'          => $fromDate,
                    'toDate'            => $toDate,
                    'status'            => $statusParam ?: null,
                    'includeDetails'    => 1,
                    'type'              => $typeParam ?: null,
                    'page'              => $page,
                ]);

            if ($response->status() === 401) {
                \Illuminate\Support\Facades\Session::flush();
                return redirect('/login')->withErrors(['login' => 'Session expired. Please login again.']);
            }

            if (!$response->successful()) {
                return back()->withErrors(['export' => 'Failed to retrieve data from server.']);
            }

            $data = $response->json();
            $recaps = collect($data['data'] ?? []);
            $allRecaps = $allRecaps->merge($recaps);

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

    // Generate Excel (sama seperti yang sudah kamu punya)
    $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setTitle('Stock Recap');

    $headers = [
        'No', 'Recap Code', 'Status Recap Code', 'Error Message Recap', 'Sold To',
        'Ship To', 'Bill To', 'Payer', 'List Recap Code', 'Material',
        'Quantity', 'Storage Location', 'Batch', 'Doctor',
        'Error Message Item', 'Status Item', 'Ref ID', 'Encounter ID'
    ];

    $col = 'A';
    foreach ($headers as $header) {
        $sheet->setCellValue($col . '1', $header);
        $col++;
    }
    $sheet->getStyle('A1:R1')->getFont()->setBold(true);

    $row = 2;
    $no = 1;

    foreach ($allRecaps as $recap) {
        $recapCode = $recap['recapCode'] ?? '-';
        $status = $recap['status'] ?? '-';
        $sapError = $recap['sapErrorMessage'] ?? '';
        $soldTo = $recap['soldTo'] ?? '';
        $shipTo = $recap['shipTo'] ?? '';
        $billTo = $recap['billTo'] ?? '';
        $payer = $recap['payer'] ?? '';
        $items = collect($recap['items'] ?? []);
        $firstRow = true;

        if ($items->isEmpty()) {
            $sheet->fromArray([[
                $no++, $recapCode, $status, $sapError, $soldTo, $shipTo, $billTo, $payer,
                '-', '-', '-', '-', '-', '-', '-', '-', '-'
            ]], null, "A{$row}");
            $row++;
        } else {
            foreach ($items as $item) {
                $refIds = collect($item['belongsToRefs'] ?? [])->pluck('refId')->filter()->unique()->implode('||');
                $encounterIds = collect($item['belongsToRefs'] ?? [])->pluck('encounterId')->filter()->unique()->implode('||');

                $sheet->setCellValue("A{$row}", $no++);
                $sheet->setCellValue("B{$row}", $firstRow ? $recapCode : '');
                $sheet->setCellValue("C{$row}", $firstRow ? $status : '');
                $sheet->setCellValue("D{$row}", $firstRow ? $sapError : '');
                $sheet->setCellValue("E{$row}", $firstRow ? $soldTo : '');
                $sheet->setCellValue("F{$row}", $firstRow ? $shipTo : '');
                $sheet->setCellValue("G{$row}", $firstRow ? $billTo : '');
                $sheet->setCellValue("H{$row}", $firstRow ? $payer : '');
                $sheet->setCellValue("I{$row}", $recapCode);
                $sheet->setCellValue("J{$row}", $item['material'] ?? '-');
                $sheet->setCellValue("K{$row}", $item['quantity'] ?? '-');
                $sheet->setCellValue("L{$row}", $item['storageLocation'] ?? '-');
                $sheet->setCellValue("M{$row}", $item['batch'] ?? '-');
                $sheet->setCellValue("N{$row}", $item['doctor'] ?? '-');
                $sheet->setCellValue("O{$row}", $item['sapErrorMessage'] ?? '');
                $sheet->setCellValue("P{$row}", $item['status'] ?? '-');
                $sheet->setCellValue("Q{$row}", $refIds);
                $sheet->setCellValue("R{$row}", $encounterIds);
                $row++;
                $firstRow = false;
            }
        }
    }

    foreach (range('A', 'R') as $col) {
        $sheet->getColumnDimension($col)->setAutoSize(true);
    }

    $fileName = 'stock' . now()->format('Ymd_His') . '.xlsx';

    // Stream response (bersihkan output buffer dulu)
    $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

    // penting: bersihkan buffer sebelum streaming agar file tidak korup/empty
    if (ob_get_level() > 0) {
        ob_end_clean();
    }

    $response = new \Symfony\Component\HttpFoundation\StreamedResponse(function () use ($writer) {
        $writer->save('php://output');
    });

    $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
    $response->headers->set('Cache-Control', 'max-age=0');

    return $response;
}
}
