<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;
use Illuminate\Http\Client\ConnectionException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AccrualController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user = session('user_name');
        $org = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        // Permission Check
        if (!user_can_data_monitoring(session('user'), 'data_monitoring_accrual')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(30)->format('Y-m-d'));
        $toDateRaw = $request->query('toDate', now()->format('Y-m-d'));
        $recapCode = $request->query('recapCode');
        $status = $request->query('status', 'success');

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ym');
            $toDate = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ym');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $page = (int) $request->query('page', 1);

        try {
            if ($recapCode) {
                // Use detail endpoint if recapCode is provided
                $url = "https://cerebro.ihc.id/api/sap/monitoring/recap/detail/{$recapCode}";
                $params = [
                    'type' => 'Accrual',
                    'salesOrganization' => $org,
                ];
            } else {
                // Use list endpoint
                $url = "https://cerebro.ihc.id/api/sap/monitoring/recap";
                $params = [
                    'limit' => 500,
                    'salesOrganization' => $org,
                    'page' => $page,
                    'fromDate' => $fromDate,
                    'toDate' => $toDate,
                    'type' => 'Accrual',
                    'status' => $status === 'all' ? 'success,failed' : $status,
                    'includeDetails' => 0,
                ];
            }

            $response = Http::withToken($token)
                ->timeout(120)
                ->get($url, $params);

        } catch (ConnectionException $e) {
            return view('accrual', [
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => collect([]),
                'error' => 'Request timeout, Unstable Connection',
                'currentPage' => $page,
                'lastPage' => 1,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
                'recapCode' => $recapCode,
                'status' => $status,
            ]);
        }

        if ($response->successful()) {
            $data = $response->json();
            $recaps = collect($data['data'] ?? []);
            
            $totalData = $data['total'] ?? $recaps->count();
            $currentPage = $data['current_page'] ?? $page;
            $lastPage = $data['last_page'] ?? (int) ceil($totalData / 500);

            return view('accrual', [
                'token' => $token,
                'user' => $user,
                'org' => $org,
                'recaps' => $recaps,
                'currentPage' => $currentPage,
                'lastPage' => $lastPage,
                'fromDate' => $fromDateRaw,
                'toDate' => $toDateRaw,
                'recapCode' => $recapCode,
                'status' => $status,
                'totalData' => $totalData,
            ]);
        }

        return view('accrual', [
            'token' => $token,
            'user' => $user,
            'org' => $org,
            'recaps' => collect([]),
            'error' => 'Failed to fetch data: ' . ($data['message'] ?? $response->body()),
            'currentPage' => $page,
            'lastPage' => 1,
            'fromDate' => $fromDateRaw,
            'toDate' => $toDateRaw,
            'recapCode' => $recapCode,
            'status' => $status,
        ]);
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $token = session('token');
        $org = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        // Permission Check
        if (!user_can_data_monitoring(session('user'), 'data_monitoring_accrual')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(30)->format('Y-m-d'));
        $toDateRaw = $request->query('toDate', now()->format('Y-m-d'));
        $status = $request->query('status', 'success');
        $recapCode = $request->query('recapCode');

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ym');
            $toDate = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ym');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid date format']);
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Accrual Monitoring');

        $headers = [
            'Recap Code',
            'SAP SO Number',
            'Accrual Type',
            'Period',
            'Document Date',
            'Final Amount',
            'Amount Free',
            'Total Amount',
            'Status',
            'Created At'
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:J1')->getFont()->setBold(true);

        $row = 2;
        $page = 1;
        $hasMore = true;
        $hasData = false;

        try {
            while ($hasMore) {
                if ($page > 1) {
                    gc_collect_cycles();
                }

                if ($recapCode) {
                    $url = "https://cerebro.ihc.id/api/sap/monitoring/recap/detail/{$recapCode}";
                    $params = [
                        'type' => 'Accrual',
                        'salesOrganization' => $org,
                    ];
                    $hasMore = false; // Detail only has one page
                } else {
                    $url = "https://cerebro.ihc.id/api/sap/monitoring/recap";
                    $params = [
                        'limit' => 500,
                        'salesOrganization' => $org,
                        'fromDate' => $fromDate,
                        'toDate' => $toDate,
                        'type' => 'Accrual',
                        'status' => $status === 'all' ? 'success,failed' : $status,
                        'includeDetails' => 0,
                        'page' => $page,
                    ];
                }

                $response = Http::withToken($token)
                    ->timeout(120)
                    ->get($url, $params);

                if (!$response->successful())
                    break;

                $data = $response->json();
                $recaps = $data['data'] ?? [];

                if (!empty($recaps)) {
                    $hasData = true;
                }

                foreach ($recaps as $recap) {
                    $sheet->setCellValue("A{$row}", $recap['recapCode']);
                    $sheet->setCellValue("B{$row}", $recap['sapSoNumber'] ?? '-');
                    $sheet->setCellValue("C{$row}", $recap['accrualType'] ?? '-');
                    $sheet->setCellValue("D{$row}", $recap['accrualPeriod'] ?? '-');
                    $sheet->setCellValue("E{$row}", $recap['documentDate'] ?? '-');
                    $sheet->setCellValue("F{$row}", $recap['totalFinalAmount'] ?? 0);
                    $sheet->setCellValue("G{$row}", $recap['totalAmountFree'] ?? 0);
                    $sheet->setCellValue("H{$row}", $recap['totalAmount'] ?? 0);
                    $sheet->setCellValue("I{$row}", $recap['status']);
                    $sheet->setCellValue("J{$row}", $recap['createdAt'] ?? '-');
                    $row++;
                }

                $currentPage = $data['current_page'] ?? $page;
                $lastPage = $data['last_page'] ?? $page;
                $hasMore = $currentPage < $lastPage;
                $page++;
            }
        } catch (ConnectionException $e) {
            return back()->withErrors(['export' => 'Request timeout or unstable connection']);
        }

        if (!$hasData) {
            return back()->withErrors(['export' => 'No data available for export']);
        }

        foreach (range('A', 'J') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $fileName = 'Accrual_Monitoring_' . now()->format('Ymd_His') . '.xlsx';

        $writer = new Xlsx($spreadsheet);
        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
