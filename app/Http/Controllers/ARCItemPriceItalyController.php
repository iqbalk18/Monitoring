<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ARCItemPriceItaly;
use App\Models\ArcItmMast;
use App\Models\Margin;
use App\Models\PriceSubmission;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ARCItemPriceItalyController extends Controller
{
    public function createPage(Request $request)
    {
        $items = ArcItmMast::orderBy('ARCIM_Code', 'asc')->get();
        $selectedArcimCode = $request->get('arcim_code');
        return view('arc_item_price_italy.create', compact('items', 'selectedArcimCode'));
    }

    public function index(Request $request)
    {
        $query = ARCItemPriceItaly::query();

        if ($request->has('arcim_code') && $request->arcim_code) {
            $query->where('ITP_ARCIM_Code', $request->arcim_code);
        }

        $prices = $query->orderBy('created_at', 'desc')->get();

        return response()->json($prices);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'ITP_ARCIM_Code' => 'required|string|max:255',
            'ITP_ARCIM_Desc' => 'nullable|string|max:255',
            'ITP_DateFrom' => 'nullable|date',
            'ITP_DateTo' => 'nullable|date',
            'ITP_TAR_Code' => 'nullable|string|max:255',
            'ITP_TAR_Desc' => 'nullable|string|max:255',
            'ITP_Price' => 'nullable|numeric',
            'ITP_CTCUR_Code' => 'nullable|string|max:255',
            'ITP_CTCUR_Desc' => 'nullable|string|max:255',
            'ITP_ROOMT_Code' => 'nullable|string|max:255',
            'ITP_ROOMT_Desc' => 'nullable|string|max:255',
            'ITP_HOSP_Code' => 'nullable|string|max:255',
            'ITP_HOSP_Desc' => 'nullable|string|max:255',
            'ITP_Rank' => 'nullable|string|max:255',
            'ITP_EpisodeType' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->expectsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $price = ARCItemPriceItaly::create($request->all());

        if ($request->expectsJson()) {
            return response()->json(['message' => 'Data successfully added', 'data' => $price], 201);
        }

        return redirect()->route('arc-item-price-italy.create')
            ->with('success', 'Data successfully added');
    }

    public function show(string $id)
    {
        $price = ARCItemPriceItaly::findOrFail($id);

        $priceData = $price->toArray();
        if ($price->ITP_DateFrom) {
            $priceData['ITP_DateFrom'] = $price->ITP_DateFrom->format('Y-m-d');
        }
        if ($price->ITP_DateTo) {
            $priceData['ITP_DateTo'] = $price->ITP_DateTo->format('Y-m-d');
        }

        return response()->json($priceData);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'ITP_ARCIM_Code' => 'required|string|max:255',
            'ITP_ARCIM_Desc' => 'nullable|string|max:255',
            'ITP_DateFrom' => 'nullable|date',
            'ITP_DateTo' => 'nullable|date',
            'ITP_TAR_Code' => 'nullable|string|max:255',
            'ITP_TAR_Desc' => 'nullable|string|max:255',
            'ITP_Price' => 'nullable|numeric',
            'ITP_CTCUR_Code' => 'nullable|string|max:255',
            'ITP_CTCUR_Desc' => 'nullable|string|max:255',
            'ITP_ROOMT_Code' => 'nullable|string|max:255',
            'ITP_ROOMT_Desc' => 'nullable|string|max:255',
            'ITP_HOSP_Code' => 'nullable|string|max:255',
            'ITP_HOSP_Desc' => 'nullable|string|max:255',
            'ITP_Rank' => 'nullable|string|max:255',
            'ITP_EpisodeType' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $price = ARCItemPriceItaly::findOrFail($id);
        $price->update($request->all());

        return response()->json(['message' => 'Data successfully updated', 'data' => $price]);
    }


    /**
     * Manage prices for a specific item.
     */
    public function managePrice(Request $request, string $arcimCode)
    {
        $item = ArcItmMast::where('ARCIM_Code', $arcimCode)->firstOrFail();

        $query = ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode);

        $status = $request->input('status', 'active');

        if ($status != '' && $status != 'all') {
            $today = now()->startOfDay();

            if ($status == 'active') {
                $query->where(function ($q) use ($today) {
                    $q->whereNull('ITP_DateTo')
                        ->orWhere('ITP_DateTo', '>=', $today);
                });
            } elseif ($status == 'non_active') {
                $query->where('ITP_DateTo', '<', $today)
                    ->whereNotNull('ITP_DateTo');
            }
        }

        $prices = $query->orderBy('created_at', 'desc')->paginate(15);

        $prices->appends($request->only(['status']));

        // Get margins for Material type (M) for Type of Item Code dropdown
        $materialMargins = Margin::where('ARCIM_ServMateria', 'M')
            ->orderBy('TypeofItemCode', 'asc')
            ->get();

        return view('arc_item_price_italy.manage', compact('item', 'prices', 'materialMargins'));
    }

    /**
     * Store price from manage page.
     */
    public function storeFromManage(Request $request, string $arcimCode)
    {
        $item = ArcItmMast::where('ARCIM_Code', $arcimCode)->firstOrFail();
        $action = $request->input('action', 'generate');

        // Handle Material (M) type
        if ($item->ARCIM_ServMaterial == 'M') {
            $rules = [
                'ITP_DateFrom' => 'required|date',
                'hna' => 'required|numeric|min:0',
                'TypeofItemCode' => 'required|string',
            ];

            $validator = Validator::make($request->all(), $rules, [
                'ITP_DateFrom.required' => 'Date From is required',
                'ITP_DateFrom.date' => 'Date From must be a valid date',
                'hna.required' => 'HNA is required',
                'hna.numeric' => 'HNA must be a number',
                'hna.min' => 'HNA must be greater than or equal to 0',
                'TypeofItemCode.required' => 'Type of Item Code is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Validation for ITP_DateTo
            $latestPrice = ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode)
                ->whereNotNull('ITP_DateTo')
                ->orderBy('ITP_DateTo', 'desc')
                ->first();

            if ($latestPrice && $latestPrice->ITP_DateTo > now()) {
                $inputDateFrom = \Carbon\Carbon::parse($request->ITP_DateFrom);
                $latestDateTo = \Carbon\Carbon::parse($latestPrice->ITP_DateTo);

                if ($inputDateFrom->lte($latestDateTo)) {
                    return redirect()->back()
                        ->withErrors(['ITP_DateFrom' => 'You have active price until (' . $latestDateTo->format('d M Y') . ')'])
                        ->withInput();
                }
            }

            // Logic for PRICE_ENTRY (Submission) or Normal Entry
            $isPriceEntry = session('user') && user_has_role(session('user'), 'PRICE_ENTRY');

            if ($isPriceEntry) {
                // Check for existing pending submission
                $existingPending = PriceSubmission::where('ITP_ARCIM_Code', $arcimCode)
                    ->where('status', 'PENDING')
                    ->exists();

                if ($existingPending) {
                    return redirect()->back()
                        ->withErrors(['submission' => 'This item still has a pending price submission.'])
                        ->withInput();
                }
            }

            // Get margin based on Type of Item Code for Material
            $typeOfItemCode = $request->TypeofItemCode;
            $margin = Margin::where('ARCIM_ServMateria', 'M')
                ->where('TypeofItemCode', $typeOfItemCode)
                ->first();

            if (!$margin) {
                return redirect()->back()
                    ->withErrors(['TypeofItemCode' => 'Margin not found for the selected Type of Item Code'])
                    ->withInput();
            }

            // Calculate price: (margin/100 + 1) × HNA
            $hna = (float) $request->hna;
            $marginValue = (float) $margin->Margin;
            $calculatedPrice = (($marginValue / 100) + 1) * $hna;

            // Decouple Episode Type: Use user selection for Episode Type
            $selectedEpisodeType = $request->ITP_EpisodeType;

            $baseData = [
                'ITP_ARCIM_Code' => $arcimCode,
                'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
                'ITP_DateFrom' => $request->ITP_DateFrom,
                'ITP_DateTo' => $request->ITP_DateTo,
                'ITP_TAR_Code' => 'REG',
                'ITP_TAR_Desc' => 'Standar',
                'ITP_CTCUR_Code' => 'IDR',
                'ITP_CTCUR_Desc' => 'Indonesian Rupiah',
                'ITP_HOSP_Code' => 'BI00',
                'ITP_HOSP_Desc' => 'Bali International Hospital',
                'ITP_Rank' => '99',
                'hna' => $hna,
                'ITP_Price' => $calculatedPrice,
                'TypeofItemCode' => $typeOfItemCode, // Save the Margin Type
                'submission_type' => 'ADD',
            ];

            $apiPrice = [
                'ITPRank' => '99',
                'ITPPrice' => (string) $calculatedPrice,
            ];

            // Map selected Episode Type to Episode Type and Room Type codes
            if (in_array($selectedEpisodeType, ['VIP', 'VVIP', 'SUITE', 'CU'])) {
                $baseData['ITP_EpisodeType'] = 'I';
                $baseData['ITP_ROOMT_Code'] = $selectedEpisodeType;

                // Get Room Type Desc from Margin if possible, or mapping, or just code
                // Since we decoupled, we might not have a direct mapping from TypeofItemCode to Room Desc if they differ
                // For simplified logic, use the selected code as description if margin description doesn't match or isn't available for this specific episode type
                // Actually, if we selected 'VIP' but margin was 'O', we don't have 'VIP' description handy unless we query it or hardcode it.
                // Let's use the code as desc for now or null, ensuring it saves.
                // Better approach: If the selected episode type matches a known Margin type, grab desc from there? 
                // Creating a comprehensive lookup or just using code as desc for now to be safe.
                $baseData['ITP_ROOMT_Desc'] = $selectedEpisodeType;

                $apiPrice['ITPEpisodeType'] = 'I';
                $apiPrice['ITPROOMTCode'] = $selectedEpisodeType;
            } else {
                $baseData['ITP_EpisodeType'] = $selectedEpisodeType;
                $apiPrice['ITPEpisodeType'] = $selectedEpisodeType;
            }

            // Collect all prices to be processed (Submission or API)
            $pricesToProcess = [];
            $pricesToProcess[] = [
                'db_data' => $baseData,
                'api_data' => $apiPrice
            ];

            // AUTOMATIC GENERATION FOR 'D' ITEMS
            // Rule: If ItemCode starts with 'D', generate automatically Episode Type = O
            // Calculation: (HNA + Margin) + 11%
            if (str_starts_with($arcimCode, 'D')) {
                // New Calculation: Price with Margin * 1.11
                $calculatedPriceO = $calculatedPrice * 1.11;

                $baseDataO = $baseData; // Copy base structure
                $baseDataO['ITP_Price'] = $calculatedPriceO;
                $baseDataO['ITP_EpisodeType'] = 'O';
                $baseDataO['ITP_Rank'] = '90'; // Update Rank to 90
                // For the auto-generated O price, we keep the TypeofItemCode (Margin Type) reference
                // But we clear Room Type fields
                unset($baseDataO['ITP_ROOMT_Code']);
                unset($baseDataO['ITP_ROOMT_Desc']);

                $apiPriceO = [
                    'ITPRank' => '90',
                    'ITPPrice' => (string) $calculatedPriceO,
                    'ITPEpisodeType' => 'O'
                ];

                $pricesToProcess[] = [
                    'db_data' => $baseDataO,
                    'api_data' => $apiPriceO
                ];
            }

            if ($isPriceEntry) {
                // Generate Batch ID
                $batchId = 'BATCH-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

                foreach ($pricesToProcess as $priceItem) {
                    $submissionData = $priceItem['db_data'];
                    $submissionData['status'] = 'PENDING';
                    $submissionData['submitted_by'] = session('user')['id'];
                    $submissionData['batch_id'] = $batchId;
                    PriceSubmission::create($submissionData);
                }

                $msg = 'Price successfully submitted and awaiting approval.';
                if (count($pricesToProcess) > 1) {
                    $msg .= ' (' . count($pricesToProcess) . ' items generated)';
                }
                return redirect()->back()->with('success', $msg);
            }

            // API Submission for Non-PRICE_ENTRY
            $apiPricesList = array_column($pricesToProcess, 'api_data');

            $apiPayload = [
                'ITPARCIMCode' => $arcimCode,
                'ITPDateFrom' => $request->ITP_DateFrom ?? '',
                'ITPDateTo' => $request->ITP_DateTo ?? '',
                'ITPTARCode' => 'REG',
                'ITPCTCURCode' => 'IDR',
                'ITPHOSPCode' => 'BI00',
                'prices' => $apiPricesList,
            ];

            try {
                $response = Http::timeout(30)->post('https://trakcare.com/api/prices', $apiPayload);

                if ($response->successful()) {
                    // Validation and auto-close previous active price
                    $newDateFrom = $request->ITP_DateFrom;
                    if ($newDateFrom) {
                        ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode)
                            ->whereNull('ITP_DateTo')
                            ->where('ITP_DateFrom', '<', $newDateFrom)
                            ->update([
                                    'ITP_DateTo' => \Carbon\Carbon::parse($newDateFrom)->subDay()->format('Y-m-d')
                                ]);
                    }
                    // Generate Batch ID for direct submission
                    $batchId = 'BATCH-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

                    foreach ($pricesToProcess as $priceItem) {
                        $dbData = $priceItem['db_data'];
                        if (!isset($dbData['batch_id'])) {
                            $dbData['batch_id'] = $batchId;
                        }
                        ARCItemPriceItaly::create($dbData);
                    }

                    Log::info('Price data (M) sent to TrakCare successfully', [
                        'arcim_code' => $arcimCode,
                        'response' => $response->json(),
                        'count' => count($pricesToProcess)
                    ]);

                    $message = 'HNA data successfully added with price: ' . number_format($calculatedPrice, 2);
                    if (count($pricesToProcess) > 1) {
                        $message .= ' (+ Auto Generated Episode O)';
                    }

                } else {
                    Log::error('Failed to send price data (M) to TrakCare', [
                        'arcim_code' => $arcimCode,
                        'status' => $response->status(),
                        'response' => $response->body(),
                    ]);

                    return redirect()->back()
                        ->withErrors(['api' => 'Failed to send data to TrakCare: ' . $response->status()])
                        ->withInput();
                }
            } catch (\Exception $e) {
                Log::error('Exception when sending to TrakCare (M)', [
                    'arcim_code' => $arcimCode,
                    'error' => $e->getMessage(),
                ]);

                return redirect()->back()
                    ->withErrors(['api' => 'Error: ' . $e->getMessage()])
                    ->withInput();
            }

            $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
            if ($request->has('status') && $request->status != '') {
                $redirectUrl .= '?status=' . $request->status;
            }

            return redirect($redirectUrl)->with('success', $message);
        }

        // Handle Service (S) type - existing logic
        $rules = [
            'ITP_DateFrom' => 'required|date',
            'ITP_Price' => 'required|numeric|min:0',
        ];

        if ($action === 'manual') {
            $rules['ITP_EpisodeType'] = 'required|string';
        }

        $validator = Validator::make($request->all(), $rules, [
            'ITP_DateFrom.required' => 'Date From is required',
            'ITP_DateFrom.date' => 'Date From must be a valid date',
            'ITP_Price.required' => 'Price is required',
            'ITP_Price.numeric' => 'Price must be a number',
            'ITP_Price.min' => 'Price must be greater than or equal to 0',
            'ITP_EpisodeType.required' => 'Episode Type is required for manual input',
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Validation for ITP_DateTo
        $latestPrice = ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode)
            ->whereNotNull('ITP_DateTo')
            ->orderBy('ITP_DateTo', 'desc')
            ->first();

        if ($latestPrice && $latestPrice->ITP_DateTo > now()) {
            $inputDateFrom = \Carbon\Carbon::parse($request->ITP_DateFrom);
            $latestDateTo = \Carbon\Carbon::parse($latestPrice->ITP_DateTo);

            if ($inputDateFrom->lte($latestDateTo)) {
                return redirect()->back()
                    ->withErrors(['ITP_DateFrom' => 'You have active price until (' . $latestDateTo->format('d M Y') . ')'])
                    ->withInput();
            }
        }

        if ($action === 'manual') {
            return $this->storeManualPrice($request, $arcimCode, $item);
        }

        if (session('user') && user_has_role(session('user'), 'PRICE_ENTRY')) {
            // Check for existing pending submission
            $existingPending = PriceSubmission::where('ITP_ARCIM_Code', $arcimCode)
                ->where('status', 'PENDING')
                ->exists();

            if ($existingPending) {
                return redirect()->back()
                    ->withErrors(['submission' => 'This item still has a pending price submission.'])
                    ->withInput();
            }

            // Generate Batch ID
            $batchId = 'BATCH-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

            $baseData = [
                'ITP_ARCIM_Code' => $arcimCode,
                'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
                'ITP_DateFrom' => $request->ITP_DateFrom,
                'ITP_DateTo' => $request->ITP_DateTo,
                'ITP_TAR_Code' => 'REG',
                'ITP_TAR_Desc' => 'Standar',
                'ITP_CTCUR_Code' => 'IDR',
                'ITP_CTCUR_Desc' => 'Indonesian Rupiah',
                'ITP_HOSP_Code' => 'BI00',
                'ITP_HOSP_Desc' => 'Bali International Hospital',
                'ITP_Rank' => '99',
                'status' => 'PENDING',
                'submitted_by' => session('user')['id'],
                'batch_id' => $batchId,
                'submission_type' => 'ADD',
            ];

            if ($request->ITP_Price !== null) {
                $margins = Margin::where('ARCIM_ServMateria', 'S')->get();

                if ($margins->count() > 0) {
                    $originalPrice = (float) $request->ITP_Price;

                    foreach ($margins as $margin) {
                        $priceData = $baseData;

                        if ($margin->TypeofItemCode == 'O') {
                            $priceData['ITP_Price'] = $originalPrice;
                            $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                        } else {
                            if ($margin->Margin !== null) {
                                if (in_array($margin->TypeofItemCode, ['VIP', 'VVIP', 'SUITE', 'CU'])) {
                                    $priceData['ITP_EpisodeType'] = 'I';
                                    $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                                    $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;
                                } else {
                                    $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                                }

                                $marginPercentage = (float) $margin->Margin;
                                $calculatedPrice = $originalPrice * ($marginPercentage / 100);
                                $priceData['ITP_Price'] = $calculatedPrice;
                            } else {
                                $priceData['ITP_Price'] = $originalPrice;

                                if (in_array($margin->TypeofItemCode, ['VIP', 'VVIP', 'SUITE', 'CU'])) {
                                    $priceData['ITP_EpisodeType'] = 'I';
                                    $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                                    $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;
                                } else {
                                    $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                                }
                            }
                        }

                        PriceSubmission::create($priceData);
                    }
                } else {
                    $baseData['ITP_Price'] = (float) $request->ITP_Price;
                    $baseData['ITP_EpisodeType'] = 'O';
                    PriceSubmission::create($baseData);
                }
            } else {
                $baseData['ITP_EpisodeType'] = 'O';
                PriceSubmission::create($baseData);
            }

            return redirect()->back()->with('success', 'Price submitted successfully and is awaiting approval.');
        }

        $baseData = [
            'ITP_ARCIM_Code' => $arcimCode,
            'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
            'ITP_DateFrom' => $request->ITP_DateFrom,
            'ITP_DateTo' => $request->ITP_DateTo,
            'ITP_TAR_Code' => 'REG',
            'ITP_TAR_Desc' => 'Standar',
            'ITP_CTCUR_Code' => 'IDR',
            'ITP_CTCUR_Desc' => 'Indonesian Rupiah',
            'ITP_HOSP_Code' => 'BI00',
            'ITP_HOSP_Desc' => 'Bali International Hospital',
            'ITP_Rank' => '99',
        ];

        $pricesForApi = [];
        $pricesForDb = [];
        $createdCount = 0;

        if ($request->ITP_Price !== null) {
            $margins = Margin::where('ARCIM_ServMateria', 'S')->get();

            if ($margins->count() > 0) {
                $originalPrice = (float) $request->ITP_Price;

                foreach ($margins as $margin) {
                    $priceData = $baseData;
                    $apiPrice = [
                        'ITPRank' => '99',
                    ];

                    if ($margin->TypeofItemCode == 'O') {
                        $priceData['ITP_Price'] = $originalPrice;
                        $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;

                        $apiPrice['ITPEpisodeType'] = $margin->TypeofItemCode;
                        $apiPrice['ITPPrice'] = (string) $originalPrice;
                    } else {
                        if ($margin->Margin !== null) {
                            if ($margin->TypeofItemCode == 'VIP' || $margin->TypeofItemCode == 'VVIP' || $margin->TypeofItemCode == 'SUITE' || $margin->TypeofItemCode == 'CU') {
                                $priceData['ITP_EpisodeType'] = 'I';
                                $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                                $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;

                                $apiPrice['ITPEpisodeType'] = 'I';
                                $apiPrice['ITPROOMTCode'] = $margin->TypeofItemCode;
                            } else {
                                $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                                $apiPrice['ITPEpisodeType'] = $margin->TypeofItemCode;
                            }

                            $marginPercentage = (float) $margin->Margin;
                            $calculatedPrice = $originalPrice * ($marginPercentage / 100);
                            $priceData['ITP_Price'] = $calculatedPrice;
                            $apiPrice['ITPPrice'] = (string) $calculatedPrice;
                        } else {
                            $priceData['ITP_Price'] = $originalPrice;

                            if ($margin->TypeofItemCode == 'VIP' || $margin->TypeofItemCode == 'VVIP' || $margin->TypeofItemCode == 'SUITE' || $margin->TypeofItemCode == 'CU') {
                                $priceData['ITP_EpisodeType'] = 'I';
                                $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                                $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;

                                $apiPrice['ITPEpisodeType'] = 'I';
                                $apiPrice['ITPROOMTCode'] = $margin->TypeofItemCode;
                            } else {
                                $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                                $apiPrice['ITPEpisodeType'] = $margin->TypeofItemCode;
                            }
                            $apiPrice['ITPPrice'] = (string) $originalPrice;
                        }
                    }

                    $pricesForDb[] = $priceData;
                    $pricesForApi[] = $apiPrice;
                    $createdCount++;
                }
            } else {
                $baseData['ITP_Price'] = (float) $request->ITP_Price;
                $baseData['ITP_EpisodeType'] = 'O';
                $pricesForDb[] = $baseData;

                $pricesForApi[] = [
                    'ITPEpisodeType' => 'O',
                    'ITPPrice' => (string) $request->ITP_Price,
                    'ITPRank' => '99',
                ];
                $createdCount = 1;
            }
        } else {
            $baseData['ITP_EpisodeType'] = 'O';
            $pricesForDb[] = $baseData;

            $pricesForApi[] = [
                'ITPEpisodeType' => 'O',
                'ITPPrice' => '0',
                'ITPRank' => '99',
            ];
            $createdCount = 1;
        }

        $apiPayload = [
            'ITPARCIMCode' => $arcimCode,
            'ITPDateFrom' => $request->ITP_DateFrom ?? '',
            'ITPDateTo' => $request->ITP_DateTo ?? '',
            'ITPTARCode' => 'REG',
            'ITPCTCURCode' => 'IDR',
            'ITPHOSPCode' => 'BI00',
            'prices' => $pricesForApi,
        ];

        try {
            $response = Http::timeout(30)->post('https://trakcare.com/api/prices', $apiPayload);

            if ($response->successful()) {
                foreach ($pricesForDb as $priceData) {
                    ARCItemPriceItaly::create($priceData);
                }

                $message = $createdCount > 1
                    ? "Data successfully added ({$createdCount} records) and sent to TrakCare"
                    : 'Data successfully added and sent to TrakCare';

                Log::info('Price data sent to TrakCare successfully', [
                    'arcim_code' => $arcimCode,
                    'response' => $response->json(),
                ]);
            } else {
                Log::error('Failed to send price data to TrakCare', [
                    'arcim_code' => $arcimCode,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return redirect()->back()
                    ->withErrors(['api' => 'Failed to send data to TrakCare: ' . $response->status()])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Exception when sending to TrakCare', [
                'arcim_code' => $arcimCode,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withErrors(['api' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }

        $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
        if ($request->has('status') && $request->status != '') {
            $redirectUrl .= '?status=' . $request->status;
        }

        return redirect($redirectUrl)->with('success', $message);
    }

    /**
     * Update price from manage page.
     */
    public function updateFromManage(Request $request, string $arcimCode, string $id)
    {
        $item = ArcItmMast::where('ARCIM_Code', $arcimCode)->firstOrFail();
        $price = ARCItemPriceItaly::findOrFail($id);

        // Handle Material (M) type
        if ($item->ARCIM_ServMaterial == 'M') {
            $validator = Validator::make($request->all(), [
                'ITP_DateFrom' => 'required|date',
                'hna' => 'required|numeric|min:0',
                'TypeofItemCode' => 'required|string',
            ], [
                'ITP_DateFrom.required' => 'Date From is required',
                'ITP_DateFrom.date' => 'Date From must be a valid date',
                'hna.required' => 'HNA is required',
                'hna.numeric' => 'HNA must be a number',
                'hna.min' => 'HNA must be greater than or equal to 0',
                'TypeofItemCode.required' => 'Type of Item Code is required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Get margin based on Type of Item Code for Material
            $typeOfItemCode = $request->TypeofItemCode;
            $margin = Margin::where('ARCIM_ServMateria', 'M')
                ->where('TypeofItemCode', $typeOfItemCode)
                ->first();

            if (!$margin) {
                return redirect()->back()
                    ->withErrors(['TypeofItemCode' => 'Margin not found for the selected Type of Item Code'])
                    ->withInput();
            }

            // Calculate price: (margin/100 + 1) × HNA
            // Margin is stored as percentage (e.g., 28 for 28%), so divide by 100 first
            $hna = (float) $request->hna;
            $marginValue = (float) $margin->Margin;
            $calculatedPrice = (($marginValue / 100) + 1) * $hna;

            // Handle Episode Type: if empty string, set to null, otherwise use the value
            $episodeType = $request->ITP_EpisodeType;
            if ($episodeType === '' || $episodeType === null) {
                $episodeType = null;
            }

            // Check if user is PRICE_ENTRY -> Create Submission
            if (session('user') && user_has_role(session('user'), 'PRICE_ENTRY')) {
                // Check for existing pending submission for this price/item
                $existingPending = PriceSubmission::where('ITP_ARCIM_Code', $arcimCode)
                    ->where('status', 'PENDING')
                    ->exists();

                if ($existingPending) {
                    return redirect()->back()
                        ->withErrors(['submission' => 'This item still has a pending price submission.'])
                        ->withInput();
                }

                $batchId = 'BATCH-UPD-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

                $submissionData = [
                    'ITP_ARCIM_Code' => $arcimCode,
                    'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
                    'ITP_DateFrom' => $request->ITP_DateFrom,
                    'ITP_DateTo' => $request->ITP_DateTo,
                    'hna' => $hna,
                    'ITP_Price' => $calculatedPrice,
                    'ITP_EpisodeType' => $episodeType,
                    // Preserve other fields from original price if needed
                    'ITP_TAR_Code' => $price->ITP_TAR_Code,
                    'ITP_TAR_Desc' => $price->ITP_TAR_Desc,
                    'ITP_CTCUR_Code' => $price->ITP_CTCUR_Code,
                    'ITP_CTCUR_Desc' => $price->ITP_CTCUR_Desc,
                    'ITP_ROOMT_Code' => $price->ITP_ROOMT_Code, // or update if needed?
                    'ITP_ROOMT_Desc' => $price->ITP_ROOMT_Desc, // or update?
                    'ITP_HOSP_Code' => $price->ITP_HOSP_Code,
                    'ITP_HOSP_Desc' => $price->ITP_HOSP_Desc,
                    'ITP_Rank' => $price->ITP_Rank,
                    'TypeofItemCode' => $typeOfItemCode,

                    'status' => 'PENDING',
                    'submitted_by' => session('user')['id'],
                    'batch_id' => $batchId,
                    'submission_type' => 'EDIT',
                    'original_price_id' => $id,
                ];

                PriceSubmission::create($submissionData);

                return redirect()->back()->with('success', 'Price change successfully submitted and awaiting approval.');
            }

            $updateData = [
                'ITP_DateFrom' => $request->ITP_DateFrom,
                'ITP_DateTo' => $request->ITP_DateTo,
                'hna' => $hna,
                'ITP_Price' => $calculatedPrice,
                'ITP_EpisodeType' => $episodeType,
            ];

            $price->update($updateData);

            $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
            if ($request->has('status') && $request->status != '') {
                $redirectUrl .= '?status=' . $request->status;
            }

            return redirect($redirectUrl)->with('success', 'HNA data successfully updated with price: ' . number_format($calculatedPrice, 2));
        }

        // Handle Service (S) type
        $validator = Validator::make($request->all(), [
            'ITP_DateFrom' => 'required|date',
            'ITP_Price' => 'required|numeric|min:0',
        ], [
            'ITP_DateFrom.required' => 'Date From is required',
            'ITP_DateFrom.date' => 'Date From must be a valid date',
            'ITP_Price.required' => 'Price is required',
            'ITP_Price.numeric' => 'Price must be a number',
            'ITP_Price.min' => 'Price must be greater than or equal to 0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        if (session('user') && user_has_role(session('user'), 'PRICE_ENTRY')) {
            // Check for existing pending submission
            $existingPending = PriceSubmission::where('ITP_ARCIM_Code', $arcimCode)
                ->where('status', 'PENDING')
                ->exists();

            if ($existingPending) {
                return redirect()->back()
                    ->withErrors(['submission' => 'This item still has a pending price submission.'])
                    ->withInput();
            }

            $batchId = 'BATCH-UPD-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());

            $submissionData = [
                'ITP_ARCIM_Code' => $arcimCode,
                'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
                'ITP_DateFrom' => $request->ITP_DateFrom,
                'ITP_DateTo' => $request->ITP_DateTo,
                'ITP_Price' => (float) $request->ITP_Price,

                // Preserve fields
                'ITP_TAR_Code' => $price->ITP_TAR_Code,
                'ITP_TAR_Desc' => $price->ITP_TAR_Desc,
                'ITP_CTCUR_Code' => $price->ITP_CTCUR_Code,
                'ITP_CTCUR_Desc' => $price->ITP_CTCUR_Desc,
                'ITP_ROOMT_Code' => $price->ITP_ROOMT_Code,
                'ITP_ROOMT_Desc' => $price->ITP_ROOMT_Desc,
                'ITP_HOSP_Code' => $price->ITP_HOSP_Code,
                'ITP_HOSP_Desc' => $price->ITP_HOSP_Desc,
                'ITP_Rank' => $price->ITP_Rank,
                'ITP_EpisodeType' => $price->ITP_EpisodeType,

                'status' => 'PENDING',
                'submitted_by' => session('user')['id'],
                'batch_id' => $batchId,
                'submission_type' => 'EDIT',
                'original_price_id' => $id,
            ];

            PriceSubmission::create($submissionData);

            return redirect()->back()->with('success', 'Price change successfully submitted and awaiting approval.');
        }

        $updateData = $request->only(['ITP_DateFrom', 'ITP_DateTo', 'ITP_Price']);

        $price->update($updateData);

        $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
        if ($request->has('status') && $request->status != '') {
            $redirectUrl .= '?status=' . $request->status;
        }

        return redirect($redirectUrl)->with('success', 'Data successfully updated');
    }

    private function storeManualPrice(Request $request, string $arcimCode, $item)
    {
        $baseData = [
            'ITP_ARCIM_Code' => $arcimCode,
            'ITP_ARCIM_Desc' => $item->ARCIM_Desc,
            'ITP_DateFrom' => $request->ITP_DateFrom,
            'ITP_DateTo' => $request->ITP_DateTo,
            'ITP_TAR_Code' => 'REG',
            'ITP_TAR_Desc' => 'Standar',
            'ITP_CTCUR_Code' => 'IDR',
            'ITP_CTCUR_Desc' => 'Indonesian Rupiah',
            'ITP_HOSP_Code' => 'BI00',
            'ITP_HOSP_Desc' => 'Bali International Hospital',
            'ITP_Rank' => '99',
        ];

        $originalPrice = (float) $request->ITP_Price;
        $episodeType = $request->ITP_EpisodeType;

        $margin = Margin::where('ARCIM_ServMateria', 'S')
            ->where('TypeofItemCode', $episodeType)
            ->first();

        $priceData = $baseData;
        $apiPrice = [
            'ITPRank' => '99',
        ];

        if ($episodeType == 'O') {

            $priceData['ITP_Price'] = $originalPrice;
            $priceData['ITP_EpisodeType'] = $episodeType;

            $apiPrice['ITPEpisodeType'] = $episodeType;
            $apiPrice['ITPPrice'] = (string) $originalPrice;
        } else {

            if ($margin && $margin->Margin !== null) {

                if (
                    $margin->TypeofItemCode == 'VIP' || $margin->TypeofItemCode == 'VVIP' ||
                    $margin->TypeofItemCode == 'SUITE' || $margin->TypeofItemCode == 'CU'
                ) {
                    $priceData['ITP_EpisodeType'] = 'I';
                    $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                    $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;

                    $apiPrice['ITPEpisodeType'] = 'I';
                    $apiPrice['ITPROOMTCode'] = $margin->TypeofItemCode;
                } else {
                    $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                    $apiPrice['ITPEpisodeType'] = $margin->TypeofItemCode;
                }

                $marginPercentage = (float) $margin->Margin;
                $calculatedPrice = $originalPrice * ($marginPercentage / 100);
                $priceData['ITP_Price'] = $calculatedPrice;
                $apiPrice['ITPPrice'] = (string) $calculatedPrice;
            } else {

                $priceData['ITP_Price'] = $originalPrice;

                if (in_array($episodeType, ['VIP', 'VVIP', 'SUITE', 'CU'])) {
                    $priceData['ITP_EpisodeType'] = 'I';
                    $priceData['ITP_ROOMT_Code'] = $episodeType;
                    $priceData['ITP_ROOMT_Desc'] = $margin ? $margin->TypeofItemDesc : $episodeType;

                    $apiPrice['ITPEpisodeType'] = 'I';
                    $apiPrice['ITPROOMTCode'] = $episodeType;
                } else {
                    $priceData['ITP_EpisodeType'] = $episodeType;
                    $apiPrice['ITPEpisodeType'] = $episodeType;
                }
                $apiPrice['ITPPrice'] = (string) $originalPrice;
            }
        }

        if (session('user') && user_has_role(session('user'), 'PRICE_ENTRY')) {
            // Check for existing pending submission
            $existingPending = PriceSubmission::where('ITP_ARCIM_Code', $arcimCode)
                ->where('status', 'PENDING')
                ->exists();

            if ($existingPending) {
                return redirect()->back()
                    ->withErrors(['submission' => 'This item still has a pending price submission.'])
                    ->withInput();
            }

            $priceData['status'] = 'PENDING';
            $priceData['submitted_by'] = session('user')['id'];

            // Generate Batch ID
            $batchId = 'BATCH-' . now()->format('YmdHis') . '-' . strtoupper(uniqid());
            $priceData['batch_id'] = $batchId;

            $priceData['batch_id'] = $batchId;
            $priceData['submission_type'] = 'ADD';

            PriceSubmission::create($priceData);

            return redirect()->back()->with('success', 'Price successfully submitted and awaiting approval.');
        }

        $apiPayload = [
            'ITPARCIMCode' => $arcimCode,
            'ITPDateFrom' => $request->ITP_DateFrom ?? '',
            'ITPDateTo' => $request->ITP_DateTo ?? '',
            'ITPTARCode' => 'REG',
            'ITPCTCURCode' => 'IDR',
            'ITPHOSPCode' => 'BI00',
            'prices' => [$apiPrice],
        ];

        try {
            $response = Http::timeout(30)->post('https://trakcare.com/api/prices', $apiPayload);

            if ($response->successful()) {
                // Validation and auto-close previous active price
                $newDateFrom = $request->ITP_DateFrom;
                if ($newDateFrom) {
                    ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode)
                        ->whereNull('ITP_DateTo')
                        ->where('ITP_DateFrom', '<', $newDateFrom)
                        ->update([
                                'ITP_DateTo' => \Carbon\Carbon::parse($newDateFrom)->subDay()->format('Y-m-d')
                            ]);
                }
                ARCItemPriceItaly::create($priceData);

                $message = 'Manual data successfully added and sent to TrakCare';

                Log::info('Manual price data sent to TrakCare successfully', [
                    'arcim_code' => $arcimCode,
                    'episode_type' => $episodeType,
                    'response' => $response->json(),
                ]);
            } else {
                Log::error('Failed to send manual price data to TrakCare', [
                    'arcim_code' => $arcimCode,
                    'status' => $response->status(),
                    'response' => $response->body(),
                ]);

                return redirect()->back()
                    ->withErrors(['api' => 'Failed to send data to TrakCare: ' . $response->status()])
                    ->withInput();
            }
        } catch (\Exception $e) {
            Log::error('Exception when sending manual data to TrakCare', [
                'arcim_code' => $arcimCode,
                'error' => $e->getMessage(),
            ]);

            return redirect()->back()
                ->withErrors(['api' => 'Error: ' . $e->getMessage()])
                ->withInput();
        }

        $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
        if ($request->has('status') && $request->status != '') {
            $redirectUrl .= '?status=' . $request->status;
        }

        return redirect($redirectUrl)->with('success', $message);
    }
}
