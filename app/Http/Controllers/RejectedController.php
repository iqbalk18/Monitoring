<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Client\ConnectionException;
use Carbon\Carbon;

class RejectedController extends Controller
{
    public function index(Request $request)
    {
        $token = session('token');
        $user  = session('user_name');
        $org   = session('sales_org');

        if (!$token) {
            return redirect('/loginmdw')->withErrors(['loginmdw' => 'Please login.']);
        }

        $fromDateRaw = $request->query('fromDate', now()->subDays(7)->format('Y-m-d'));
        $toDateRaw   = $request->query('toDate', now()->format('Y-m-d'));

        try {
            $fromDate = Carbon::createFromFormat('Y-m-d', $fromDateRaw)->format('Ymd');
            $toDate   = Carbon::createFromFormat('Y-m-d', $toDateRaw)->format('Ymd');
        } catch (\Exception $e) {
            return back()->withErrors(['date' => 'Invalid Format Date']);
        }

        $allDataTypes = ['Billing', 'StockConsumption', 'StockReturn'];
        $dataType = $request->query('dataType', $allDataTypes);
        if (!is_array($dataType)) $dataType = [$dataType];

        $allOrderTypes = ['ZJSC','ZJME','ZJBJ','ZJBC','ZISC','ZIRE','ZIM2','ZIM1','ZIBJ','ZIBC'];
        $orderType = $request->query('orderType', $allOrderTypes);
        if (!is_array($orderType)) $orderType = [$orderType];


        $page = (int) $request->query('page', 1);

        try {
            $response = Http::withToken($token)
                ->timeout(30)
                ->get('https://cerebro.ihc.id/api/sap/monitoring/rejected-raw', [
                    'includeDetails'    => 1,
                    'limit'             => 100,
                    'dataType' => count($dataType) === count($allDataTypes) ? null : implode(',', $dataType),
                    'salesOrganization' => $org,
                    'page'              => $page,
                    // only include orderType if user selected any; else send nothing (treat as all)
                    'orderType' => count($orderType) === count($allOrderTypes) ? null : implode(',', $orderType),
                    'fromDate'          => $fromDate,
                    'toDate'            => $toDate,
                ]);
        } catch (ConnectionException $e) {
            return view('rejected', [
                'user'        => $user,
                'org'         => $org,
                'rejected'    => collect([]),
                'error'       => 'Request timeout, unstable connection',
                'currentPage' => $page,
                'lastPage'    => 1,
                'total'       => 0,
                'dataType'    => $dataType,
                'orderType'   => $orderType,
                'fromDate'    => $fromDateRaw,
                'toDate'      => $toDateRaw,
            ]);
        }

        if (!$response->successful()) {
            return view('rejected', [
                'user'        => $user,
                'org'         => $org,
                'rejected'    => collect([]),
                'error'       => 'Failed to get data: ' . $response->body(),
                'currentPage' => $page,
                'lastPage'    => 1,
                'total'       => 0,
                'dataType'    => $dataType,
                'orderType'   => $orderType,
                'fromDate'    => $fromDateRaw,
                'toDate'      => $toDateRaw,
            ]);
        }

        $data = $response->json();

        return view('rejected', [
            'user'        => $user,
            'org'         => $org,
            'rejected'    => collect($data['data'] ?? []),
            'currentPage' => $data['current_page'] ?? $page,
            'lastPage'    => $data['last_page'] ?? 1,
            'total'       => $data['total'] ?? 0,
            'dataType'    => $dataType,
            'orderType'   => $orderType,
            'fromDate'    => $fromDateRaw,
            'toDate'      => $toDateRaw,
        ]);
    }
}
