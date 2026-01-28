<?php

namespace App\Http\Controllers;

use App\Models\PriceSubmission;
use App\Models\ARCItemPriceItaly;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PriceSubmissionController extends Controller
{
    public function index(Request $request)
    {
        $role = session('user')['role'] ?? null;

        $query = PriceSubmission::select('batch_id', 'ITP_ARCIM_Code', 'ITP_ARCIM_Desc', 'created_at', 'submitted_by', 'submission_type', 'approved_by')
            ->selectRaw('COUNT(*) as total_items')
            ->selectRaw('MIN(id) as id') // Use one ID for linking
            ->with(['submitter', 'approver'])
            ->groupBy('batch_id', 'ITP_ARCIM_Code', 'ITP_ARCIM_Desc', 'created_at', 'submitted_by', 'submission_type', 'approved_by')
            ->orderBy('created_at', 'desc');

        if ($role == 'PRICE_APPROVER') {
            // Show all statuses (PENDING, APPROVED, REJECTED)
            // $query->where('status', 'PENDING');
        } elseif ($role == 'PRICE_ENTRY') {
            $query->where('submitted_by', session('user')['id']);
        }

        // Search Filter (Batch ID, Item Code, Description, Submitted By)
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('batch_id', 'like', "%{$search}%")
                    ->orWhere('ITP_ARCIM_Code', 'like', "%{$search}%")
                    ->orWhere('ITP_ARCIM_Desc', 'like', "%{$search}%")
                    ->orWhereHas('submitter', function ($subQ) use ($search) {
                        $subQ->where('username', 'like', "%{$search}%");
                    });
            });
        }

        // Date Filter
        if ($request->filled('date_filter')) {
            $query->whereDate('created_at', $request->date_filter);
        }

        $submissions = $query->paginate(15)->withQueryString();

        return view('price_submissions.index', compact('submissions'));
    }

    public function show($id)
    {
        // Find one to get the code, then fetch all pending for that code
        $baseSubmission = PriceSubmission::findOrFail($id);

        $submissions = PriceSubmission::with(['item', 'submitter', 'approver'])
            ->where('ITP_ARCIM_Code', $baseSubmission->ITP_ARCIM_Code)
            ->where('status', $baseSubmission->status) // Show same status group
            ->get();

        return view('price_submissions.show', compact('submissions', 'baseSubmission'));
    }

    public function approve(Request $request, $id)
    {
        if (session('user')['role'] !== 'PRICE_APPROVER') {
            return redirect()->back()->withErrors(['access' => 'Unauthorized access.']);
        }

        $baseSubmission = PriceSubmission::findOrFail($id);

        // Fetch all PENDING submissions for this BATCH
        // If batch_id exists, use it. Fallback to old logic if null (legacy support)
        if ($baseSubmission->batch_id) {
            $submissions = PriceSubmission::where('batch_id', $baseSubmission->batch_id)
                ->where('status', 'PENDING')
                ->get();
        } else {
            $submissions = PriceSubmission::where('ITP_ARCIM_Code', $baseSubmission->ITP_ARCIM_Code)
                ->where('created_at', $baseSubmission->created_at) // More specific than just status
                ->where('status', 'PENDING')
                ->get();
        }

        if ($submissions->isEmpty()) {
            return redirect()->back()->withErrors(['status' => 'No pending submissions found for this batch.']);
        }

        // Prepare TrakCare Payload (Batch)
        $pricesForApi = [];
        $pricesForDb = [];

        foreach ($submissions as $sub) {
            $apiPrice = [
                'ITPEpisodeType' => $sub->ITP_EpisodeType,
                'ITPROOMTCode' => $sub->ITP_ROOMT_Code ?? '', // Ensure empty string if null
                'ITPPrice' => (string) (int) $sub->ITP_Price,
                'ITPRank' => (string) $sub->ITP_Rank,
            ];
            $pricesForApi[] = $apiPrice;
        }

        // Use details from the first submission for the main payload info
        $first = $submissions->first();
        $apiPayload = [
            'ITPARCIMCode' => $first->ITP_ARCIM_Code,
            'ITPDateFrom' => $first->ITP_DateFrom ? $first->ITP_DateFrom : '',
            'ITPDateTo' => $first->ITP_DateTo ? $first->ITP_DateTo : '',
            'ITPTARCode' => $first->ITP_TAR_Code,
            'ITPCTCURCode' => $first->ITP_CTCUR_Code,
            'ITPHOSPCode' => $first->ITP_HOSP_Code,
            'prices' => $pricesForApi,
        ];

        // Log the JSON payload for verification
        Log::info('Price Approval Payload: ' . json_encode($apiPayload, JSON_PRETTY_PRINT));

        try {
            $response = Http::timeout(30)->post('https://trakcare.com/api/prices', $apiPayload);

            if ($response->successful()) {
                // Create Real Records and Update Status
                // Logic to close previous active price
                $newDateFrom = $first->ITP_DateFrom;
                if ($newDateFrom) {
                    ARCItemPriceItaly::where('ITP_ARCIM_Code', $first->ITP_ARCIM_Code)
                        ->whereNull('ITP_DateTo')
                        ->where('ITP_DateFrom', '<', $newDateFrom)
                        ->update([
                            'ITP_DateTo' => \Carbon\Carbon::parse($newDateFrom)->subDay()->format('Y-m-d')
                        ]);
                }

                foreach ($submissions as $sub) {
                    if ($sub->submission_type === 'EDIT' && $sub->original_price_id) {
                        $existing = ARCItemPriceItaly::find($sub->original_price_id);
                        if ($existing) {
                            $existing->update([
                                'ITP_DateFrom' => $sub->ITP_DateFrom,
                                'ITP_DateTo' => $sub->ITP_DateTo,
                                'ITP_Price' => $sub->ITP_Price,
                                'ITP_EpisodeType' => $sub->ITP_EpisodeType,
                                'hna' => $sub->hna,
                            ]);
                        } else {
                            // Fallback: Create new if original not found
                            ARCItemPriceItaly::create([
                                'ITP_ARCIM_Code' => $sub->ITP_ARCIM_Code,
                                'ITP_ARCIM_Desc' => $sub->ITP_ARCIM_Desc,
                                'ITP_DateFrom' => $sub->ITP_DateFrom,
                                'ITP_DateTo' => $sub->ITP_DateTo,
                                'ITP_TAR_Code' => $sub->ITP_TAR_Code,
                                'ITP_TAR_Desc' => $sub->ITP_TAR_Desc,
                                'ITP_Price' => $sub->ITP_Price,
                                'ITP_CTCUR_Code' => $sub->ITP_CTCUR_Code,
                                'ITP_CTCUR_Desc' => $sub->ITP_CTCUR_Desc,
                                'ITP_ROOMT_Code' => $sub->ITP_ROOMT_Code,
                                'ITP_ROOMT_Desc' => $sub->ITP_ROOMT_Desc,
                                'ITP_HOSP_Code' => $sub->ITP_HOSP_Code,
                                'ITP_HOSP_Desc' => $sub->ITP_HOSP_Desc,
                                'ITP_Rank' => $sub->ITP_Rank,
                                'ITP_EpisodeType' => $sub->ITP_EpisodeType,
                                'batch_id' => $sub->batch_id,
                                'TypeofItemCode' => $sub->TypeofItemCode,
                                'hna' => $sub->hna,
                            ]);
                        }
                    } else {
                        ARCItemPriceItaly::create([
                            'ITP_ARCIM_Code' => $sub->ITP_ARCIM_Code,
                            'ITP_ARCIM_Desc' => $sub->ITP_ARCIM_Desc,
                            'ITP_DateFrom' => $sub->ITP_DateFrom,
                            'ITP_DateTo' => $sub->ITP_DateTo,
                            'ITP_TAR_Code' => $sub->ITP_TAR_Code,
                            'ITP_TAR_Desc' => $sub->ITP_TAR_Desc,
                            'ITP_Price' => $sub->ITP_Price,
                            'ITP_CTCUR_Code' => $sub->ITP_CTCUR_Code,
                            'ITP_CTCUR_Desc' => $sub->ITP_CTCUR_Desc,
                            'ITP_ROOMT_Code' => $sub->ITP_ROOMT_Code,
                            'ITP_ROOMT_Desc' => $sub->ITP_ROOMT_Desc,
                            'ITP_HOSP_Code' => $sub->ITP_HOSP_Code,
                            'ITP_HOSP_Desc' => $sub->ITP_HOSP_Desc,
                            'ITP_Rank' => $sub->ITP_Rank,
                            'ITP_EpisodeType' => $sub->ITP_EpisodeType,
                            'batch_id' => $sub->batch_id,
                            'TypeofItemCode' => $sub->TypeofItemCode,
                            'hna' => $sub->hna,
                        ]);
                    }

                    $sub->update([
                        'status' => 'APPROVED',
                        'approved_by' => session('user')['id'],
                    ]);
                }

                return redirect()->route('price-submissions.index')->with('success', 'Batch prices approved and synced to TrakCare.');
            } else {
                Log::error('Approve: Failed to send to TrakCare', ['response' => $response->body()]);
                return redirect()->back()->withErrors(['api' => 'Failed to sync to TrakCare: ' . $response->status()]);
            }
        } catch (\Exception $e) {
            Log::error('Approve: Exception', ['error' => $e->getMessage()]);
            return redirect()->back()->withErrors(['api' => 'Exception: ' . $e->getMessage()]);
        }
    }

    public function reject(Request $request, $id)
    {
        if (session('user')['role'] !== 'PRICE_APPROVER') {
            return redirect()->back()->withErrors(['access' => 'Unauthorized access.']);
        }

        $request->validate(['rejection_reason' => 'required|string']);

        $baseSubmission = PriceSubmission::findOrFail($id);

        // Fetch all PENDING submissions for this item
        $submissions = PriceSubmission::where('ITP_ARCIM_Code', $baseSubmission->ITP_ARCIM_Code)
            ->where('status', 'PENDING')
            ->get();

        foreach ($submissions as $sub) {
            $sub->update([
                'status' => 'REJECTED',
                'approved_by' => session('user')['id'],
                'rejection_reason' => $request->rejection_reason,
            ]);
        }

        return redirect()->route('price-submissions.index')->with('success', 'Batch prices rejected.');
    }
}
