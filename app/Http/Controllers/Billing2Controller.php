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

class Billing2Controller extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user = session('user_name');
        $org = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please Login.']);
        }

        if (!user_has_role(session('user'), 'ADMIN')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        if (!user_has_role(session('user'), 'ADMIN')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
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

        $refId = $request->query('refId');
        $recapCode = $request->query('recapCode');

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
                    'refId' => $refId,
                    'recapCode' => $recapCode,
                ]);
        } catch (ConnectionException $e) {
            return view('billing2', [
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
                'refId' => $refId,
                'recapCode' => $recapCode,
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

            // Client-side filtering for Ref ID
            if (!empty($refId)) {
                $recaps = $recaps->filter(function ($recap) use ($refId) {
                    $refs = collect($recap['refs'] ?? []);
                    return $refs->contains(function ($ref) use ($refId) {
                        return stripos($ref['refId'], $refId) !== false;
                    });
                })->values();
            }

            // Client-side filtering for Recap Code
            if (!empty($recapCode)) {
                $recaps = $recaps->filter(function ($recap) use ($recapCode) {
                    return stripos($recap['recapCode'], $recapCode) !== false;
                })->values();
            }

            $recapCount = $recaps->count();

            // Calculate totals from filtered data
            // Any existing 'additional' data from API is invalid if we filtered locally
            $shouldRecalculate = !empty($refId) || !empty($recapCode) || $status === 'failed';

            $totalFinalAmount = (!$shouldRecalculate && isset($data['additional']['totalFinalAmount']))
                ? (int) $data['additional']['totalFinalAmount']
                : (int) $recaps->sum(fn($r) => collect($r['refs'] ?? [])->sum('totalFinalAmount'));

            // Note: API might allow top-level totalFinalAmount, but if filtered by RefID, matches might be partial?
            // Actually, if we filter by RefID, we keep the WHOLE recap if it has the refid.
            // But usually totals should reflect the filtered view.
            // For now, simple sum of the recap's totals is safest.
            if ($shouldRecalculate && $recaps->isNotEmpty() && isset($recaps->first()['totalFinalAmount'])) {
                // Optimization: use top level keys if available and trustworthy, but for safety let's sum keys
                $totalFinalAmount = $recaps->sum('totalFinalAmount');
            }


            $totalAmount = (!$shouldRecalculate && isset($data['additional']['totalAmount']))
                ? (int) $data['additional']['totalAmount']
                : (int) $recaps->sum('totalAmount');

            $totalAmountFree = (!$shouldRecalculate && isset($data['additional']['totalAmountFree']))
                ? (int) $data['additional']['totalAmountFree']
                : (int) $recaps->sum('totalAmountFree');

            $totalDepositAmount = (!$shouldRecalculate && isset($data['additional']['totalDepositAmount']))
                ? (int) $data['additional']['totalDepositAmount']
                : (int) $recaps->sum(fn($r) => collect($r['refs'] ?? [])->sum('depositAmount'));

            // if top level depositAmount exists on recap
            if ($shouldRecalculate) {
                // Fallback for deposit if it's not in refs? API structure isn't fully clear on where deposit lives,
                // assuming it sums up or lives in 'additional'.
                // Let's rely on what we have.
                // Previous code: (int) ($data['additional']['totalDepositAmount'] ?? 0);
                // There is no recap->sum('depositAmount') in original code lines 106-108.
                // But lines 274 in export use $ref['depositAmount'].
                // So we should calculate it.
                $totalDepositAmount = $recaps->reduce(function ($carry, $recap) {
                    return $carry + collect($recap['refs'] ?? [])->sum('depositAmount');
                }, 0);
            }


            $totalData = $shouldRecalculate ? $recaps->count() : ($data['total'] ?? $recapsRaw->count());

            $currentPage = $data['current_page'] ?? $page;
            $lastPage = $data['last_page'] ?? (int) ceil(($totalData ?: $recapsRaw->count()) / 5);

            return view('billing2', [
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
                'refId' => $refId,
                'recapCode' => $recapCode,
            ]);
        }

        return view('billing2', [
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
            'refId' => $refId,
            'recapCode' => $recapCode,
        ]);
    }


    public function exportExcel(Request $request)
    {
        // Increase memory limit and execution time for large exports
        ini_set('memory_limit', '-1');
        set_time_limit(0);

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

        if (!user_has_role(session('user'), 'ADMIN')) {
            return redirect('/home')->withErrors(['access' => 'Unauthorized access.']);
        }

        $status = $request->query('status', []);
        $typeFilter = $request->query('type', 'FinalBilling');
        $refId = $request->query('refId');
        $recapCode = $request->query('recapCode');

        if (!is_array($status))
            $status = [$status];

        // === INITIALIZE EXCEL ===
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Billing Recap');

        $headers = [
            'Ref ID',
            'Document Date',
            'Order Type',
            'Recap Code',
            'FB Status',
            'Recap Status',
            'Payer',
            'Assignment',
            'Deposit Amount',
            'Final Amount',
            'Amount Free',
            'Total Amount',
            'SAP Error Message'
        ];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $col++;
        }
        $sheet->getStyle('A1:M1')->getFont()->setBold(true);

        $row = 2;
        $page = 1;
        $hasMore = true;
        $hasData = false;

        try {
            while ($hasMore) {
                if ($page > 1) {
                    gc_collect_cycles();
                }

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
                        'refId' => $refId,
                        'recapCode' => $recapCode,
                    ]);

                if (!$response->successful())
                    break;

                $data = $response->json();
                $recaps = $data['data'] ?? []; // Defined here

                if (!empty($recaps)) {
                    $hasData = true;
                }

                // Process current page data directly to Excel
                foreach ($recaps as $recap) {
                    // Filter by Recap Code
                    if (!empty($recapCode) && stripos($recap['recapCode'], $recapCode) === false) {
                        continue;
                    }

                    $refs = collect($recap['refs'] ?? []);

                    // Filter by Ref ID
                    if (!empty($refId)) {
                        $refs = $refs->filter(function ($ref) use ($refId) {
                            return stripos($ref['refId'], $refId) !== false;
                        });

                        // If filtering by Ref ID and no refs match, skip this recap
                        if ($refs->isEmpty()) {
                            continue;
                        }
                    }

                    if ($refs->isEmpty()) {
                        // If no refs (and not filtered out by empty ref search), show header info
                        $refs = collect([
                            [
                                'refId' => '-',
                                'documentDate' => $recap['documentDate'] ?? '-',
                                'depositAmount' => 0,
                                'totalFinalAmount' => $recap['totalFinalAmount'] ?? 0,
                                'totalAmountFree' => $recap['totalAmountFree'] ?? 0,
                                'totalAmount' => $recap['totalAmount'] ?? 0,
                            ]
                        ]);
                    }

                    // Prepare Error Message
                    $sapError = $recap['sapErrorMessage'] ?? '';
                    if (empty($sapError) && !empty($recap['items'])) {
                        $itemErrors = [];
                        foreach ($recap['items'] as $item) {
                            if (!empty($item['sapErrorMessage'])) {
                                $refIds = collect($item['belongsToRefs'] ?? [])->pluck('refId')->unique();
                                $formattedRefIds = $refIds->map(fn($id) => "- $id")->implode("\n");
                                // If there are Ref IDs, prepend them. Otherwise just the error.
                                $errorPart = "- {$item['sapErrorMessage']}";
                                $itemErrors[] = $formattedRefIds ? "{$formattedRefIds}\n{$errorPart}" : $errorPart;
                            }
                        }
                        if (!empty($itemErrors)) {
                            $sapError = implode("; ", $itemErrors);
                        }
                    }

                    foreach ($refs as $ref) {
                        // Columns: Ref ID, Document Date, Order Type, Recap Code, FB Status, Recap Status, Payer, Assignment, Deposit Amount, Final Amount, Amount Free, Total Amount, SAP Error Message
                        $sheet->setCellValue("A{$row}", $ref['refId'] ?? '-');
                        $sheet->setCellValue("B{$row}", $ref['documentDate'] ?? '-');
                        $sheet->setCellValue("C{$row}", $recap['orderType'] ?? '-');
                        $sheet->setCellValue("D{$row}", $recap['recapCode'] ?? '-');
                        $sheet->setCellValue("E{$row}", $recap['fbStatus'] ?? '-');
                        $sheet->setCellValue("F{$row}", $recap['status'] ?? '-');
                        $sheet->setCellValue("G{$row}", $recap['payer'] ?? '-');
                        $sheet->setCellValue("H{$row}", $recap['assignment'] ?? '-');
                        $sheet->setCellValue("I{$row}", $ref['depositAmount'] ?? 0);
                        $sheet->setCellValue("J{$row}", $ref['totalFinalAmount'] ?? 0);
                        $sheet->setCellValue("K{$row}", $ref['totalAmountFree'] ?? 0);
                        $sheet->setCellValue("L{$row}", $ref['totalAmount'] ?? 0);
                        $sheet->setCellValue("M{$row}", $sapError);
                        $row++;
                    }
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
