<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class AccrualController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user  = session('user_name');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        if (!user_can_data_monitoring(session('user'), 'data_monitoring_accrual')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(30)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate',   now()->format('Y-m-d'));
        $recapCode   = $request->query('recapCode');
        $status      = $request->query('status', 'success');

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ym');
            $toDate   = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ym');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $page = (int) $request->query('page', 1);

        if ($recapCode) {
            $url    = "https://cerebro.ihc.id/api/sap/monitoring/recap/detail/{$recapCode}";
            $params = [
                'type'              => 'Accrual',
                'salesOrganization' => $org,
            ];
        } else {
            $url    = "https://cerebro.ihc.id/api/sap/monitoring/recap";
            $params = [
                'limit'             => 50,
                'salesOrganization' => $org,
                'page'              => $page,
                'fromDate'          => $fromDate,
                'toDate'            => $toDate,
                'type'              => 'Accrual',
                'status'            => $status === 'all' ? 'success,failed' : $status,
                'includeDetails'    => 1,
            ];
        }

        $result = $this->curlGet($url, $params, $token);

        if (!$result['ok']) {
            return view('accrual', [
                'token'       => $token,
                'user'        => $user,
                'org'         => $org,
                'recaps'      => collect([]),
                'error'       => $result['error'] ?? 'Failed to fetch data',
                'currentPage' => $page,
                'lastPage'    => 1,
                'fromDate'    => $fromDateRaw,
                'toDate'      => $toDateRaw,
                'recapCode'   => $recapCode,
                'status'      => $status,
            ]);
        }

        $data      = $result['data'];
        $recaps    = collect($data['data'] ?? []);
        $totalData = $data['total']        ?? $recaps->count();
        $currPage  = $data['current_page'] ?? $page;
        $lastPage  = $data['last_page']    ?? (int) ceil($totalData / 50);

        return view('accrual', [
            'token'       => $token,
            'user'        => $user,
            'org'         => $org,
            'recaps'      => $recaps,
            'currentPage' => $currPage,
            'lastPage'    => $lastPage,
            'fromDate'    => $fromDateRaw,
            'toDate'      => $toDateRaw,
            'recapCode'   => $recapCode,
            'status'      => $status,
            'totalData'   => $totalData,
        ]);
    }

    public function exportExcel(Request $request)
    {
        ini_set('memory_limit', '-1');
        set_time_limit(0);

        $token = session('token');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        if (!user_can_data_monitoring(session('user'), 'data_monitoring_accrual')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(30)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate',   now()->format('Y-m-d'));
        $status      = $request->query('status', 'success');
        $recapCode   = $request->query('recapCode');

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ym');
            $toDate   = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ym');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid date format']);
        }

        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Accrual Monitoring');

        $headers = [
            'Recap Code', 'SAP SO Number', 'Payer', 'Accrual Type', 'Period',
            'Document Date', 'Total Final Amount', 'Total Amount Free', 'Total Amount',
            'Material', 'Ref ID', 'Item Qty', 'Item Amount', 'Amount Free Item',
            'Status', 'Created At',
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:P1')->getFont()->setBold(true);

        $row     = 2;
        $page    = 1;
        $hasMore = true;
        $hasData = false;

        while ($hasMore) {
            if ($page > 1) {
                gc_collect_cycles();
            }

            if ($recapCode) {
                $url    = "https://cerebro.ihc.id/api/sap/monitoring/recap/detail/{$recapCode}";
                $params = ['type' => 'Accrual', 'salesOrganization' => $org];
                $hasMore = false;
            } else {
                $url    = "https://cerebro.ihc.id/api/sap/monitoring/recap";
                $params = [
                    'limit'             => 50,
                    'salesOrganization' => $org,
                    'fromDate'          => $fromDate,
                    'toDate'            => $toDate,
                    'type'              => 'Accrual',
                    'status'            => $status === 'all' ? 'success,failed' : $status,
                    'includeDetails'    => 1,
                    'page'              => $page,
                ];
            }

            $result = $this->curlGet($url, $params, $token);

            if (!$result['ok']) {
                break;
            }

            $data   = $result['data'];
            $recaps = $data['data'] ?? [];

            if (!empty($recaps)) {
                $hasData = true;
            }

            foreach ($recaps as $recap) {
                $items = $recap['items'] ?? [[]];
                if (empty($items)) $items = [[]];

                foreach ($items as $item) {
                    $refs = $item['belongsToRefs'] ?? [[]];
                    if (empty($refs)) $refs = [[]];

                    foreach ($refs as $ref) {
                        $sheet->setCellValue("A{$row}", $recap['recapCode']);
                        $sheet->setCellValue("B{$row}", $recap['sapSoNumber']     ?? '-');
                        $sheet->setCellValue("C{$row}", $recap['payer']           ?? '-');
                        $sheet->setCellValue("D{$row}", $recap['accrualType']     ?? '-');
                        $sheet->setCellValue("E{$row}", $recap['accrualPeriod']   ?? '-');
                        $sheet->setCellValue("F{$row}", $recap['documentDate']    ?? '-');
                        $sheet->setCellValue("G{$row}", $recap['totalFinalAmount'] ?? 0);
                        $sheet->setCellValue("H{$row}", $recap['totalAmountFree'] ?? 0);
                        $sheet->setCellValue("I{$row}", $recap['totalAmount']     ?? 0);

                        // Item details
                        $sheet->setCellValue("J{$row}", $item['material'] ?? '-');
                        $sheet->setCellValue("K{$row}", $ref['refId'] ?? '-');

                        $sheet->setCellValue("L{$row}", $item['quantity'] ?? 0);
                        $sheet->setCellValue("M{$row}", $item['finalAmount'] ?? 0);
                        $sheet->setCellValue("N{$row}", $item['amountFree'] ?? 0);

                        $sheet->setCellValue("O{$row}", $recap['status']);
                        $sheet->setCellValue("P{$row}", $recap['createdAt']       ?? '-');
                        $row++;
                    }
                }
            }

            $currentPage = $data['current_page'] ?? $page;
            $lastPage    = $data['last_page']    ?? $page;
            $hasMore     = $currentPage < $lastPage;
            $page++;
        }

        if (!$hasData) {
            return back()->withErrors(['export' => 'No data available for export']);
        }

        foreach (range('A', 'P') as $c) {
            $sheet->getColumnDimension($c)->setAutoSize(true);
        }

        $fileName = 'Accrual_Monitoring_' . now()->format('Ymd_His') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $fileName, [
            'Content-Type'  => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }

    // -------------------------------------------------------------------------
    // Raw cURL helper
    // -------------------------------------------------------------------------

    /**
     * Perform a GET request using raw cURL.
     *
     * WHY raw cURL instead of Laravel Http / Guzzle?
     *
     * cURL error 18 (CURLE_PARTIAL_FILE) means the server sent fewer bytes
     * than its Content-Length header declared, and libcurl aborts.
     * Guzzle surfaces this as an uncatchable transfer exception — there is no
     * Guzzle-level flag to suppress it.
     *
     * With raw cURL we can set CURLOPT_IGNORE_CONTENT_LENGTH = true, which
     * tells libcurl to accept whatever bytes actually arrive and NOT compare
     * them against Content-Length.  The response body is then valid JSON that
     * we can decode normally.
     *
     * Additional hardening:
     *   - Accept-Encoding: identity — no compression, removes gzip-truncation risk
     *   - Connection: close         — no keep-alive socket reuse
     *   - HTTP/1.1 explicit         — chunked transfer behaves predictably
     *   - 3 retries with back-off   — network blips
     *
     * @return array{ok: bool, status: int, data: array|null, error: string|null}
     */
    private function curlGet(string $url, array $params, string $token, int $maxRetries = 3): array
    {
        $fullUrl = $url . '?' . http_build_query($params);

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {

            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL                   => $fullUrl,
                CURLOPT_RETURNTRANSFER        => true,
                CURLOPT_HEADER                => false,
                CURLOPT_HTTPHEADER            => [
                    'Authorization: Bearer ' . $token,
                    'Accept: application/json',
                    'Accept-Encoding: identity',   // Disable gzip/deflate/br — reduces truncation risk
                    'Connection: close',           // No keep-alive reuse
                ],
                // ↓ THE KEY FIX: ignore mismatched Content-Length header
                CURLOPT_IGNORE_CONTENT_LENGTH => true,
                CURLOPT_HTTP_VERSION          => CURL_HTTP_VERSION_1_1,
                CURLOPT_FOLLOWLOCATION        => true,
                CURLOPT_MAXREDIRS             => 5,
                CURLOPT_TIMEOUT               => 180,
                CURLOPT_CONNECTTIMEOUT        => 30,
                CURLOPT_FAILONERROR           => false,   // Don't abort on 4xx/5xx
            ]);

            $body     = curl_exec($ch);
            $errno    = curl_errno($ch);
            $errMsg   = curl_error($ch);
            $httpCode = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            // errno 0  = clean success
            // errno 18 = CURLE_PARTIAL_FILE — server closed early but we still
            //            have data because IGNORE_CONTENT_LENGTH let us keep reading
            $transferOk = ($errno === 0 || $errno === 18) && $body !== false && $body !== '';

            if ($transferOk) {
                $decoded = json_decode($body, true);

                if (json_last_error() === JSON_ERROR_NONE) {
                    return [
                        'ok'     => $httpCode >= 200 && $httpCode < 300,
                        'status' => $httpCode,
                        'data'   => $decoded,
                        'error'  => null,
                    ];
                }

                // JSON was truncated mid-stream; retry
                $errMsg = 'JSON decode error: ' . json_last_error_msg();
            }

            // Back-off before retry (1 s, 2 s, …)
            if ($attempt < $maxRetries) {
                sleep($attempt);
            }
        }

        return [
            'ok'     => false,
            'status' => 0,
            'data'   => null,
            'error'  => "cURL failed after {$maxRetries} attempts. Last error [{$errno}]: {$errMsg}",
        ];
    }
}
