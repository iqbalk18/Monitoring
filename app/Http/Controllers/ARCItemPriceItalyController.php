<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ARCItemPriceItaly;
use App\Models\ArcItmMast;
use App\Models\Margin;
use Illuminate\Support\Facades\Validator;

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
            return response()->json(['message' => 'Data berhasil ditambahkan', 'data' => $price], 201);
        }

        return redirect()->route('arc-item-price-italy.create')
            ->with('success', 'Data berhasil ditambahkan');
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

        return response()->json(['message' => 'Data berhasil diupdate', 'data' => $price]);
    }


    /**
     * Manage prices for a specific item.
     */
    public function managePrice(Request $request, string $arcimCode)
    {
        $item = ArcItmMast::where('ARCIM_Code', $arcimCode)->firstOrFail();
        
        $query = ARCItemPriceItaly::where('ITP_ARCIM_Code', $arcimCode);

        if ($request->has('status') && $request->status != '') {
            $today = now()->startOfDay();
            
            if ($request->status == 'active') {
                $query->where(function($q) use ($today) {
                    $q->whereNull('ITP_DateTo')
                      ->orWhere('ITP_DateTo', '>=', $today);
                });
            } elseif ($request->status == 'non_active') {
                $query->where('ITP_DateTo', '<', $today)
                      ->whereNotNull('ITP_DateTo');
            }
        }

        $prices = $query->orderBy('created_at', 'desc')->paginate(15);
        
        $prices->appends($request->only(['status']));

        return view('arc_item_price_italy.manage', compact('item', 'prices'));
    }

    /**
     * Store price from manage page.
     */
    public function storeFromManage(Request $request, string $arcimCode)
    {
        $validator = Validator::make($request->all(), [
            'ITP_DateFrom' => 'required|date',
            'ITP_Price' => 'required|numeric|min:0',
        ], [
            'ITP_DateFrom.required' => 'Date From wajib diisi',
            'ITP_DateFrom.date' => 'Date From harus berupa tanggal yang valid',
            'ITP_Price.required' => 'Price wajib diisi',
            'ITP_Price.numeric' => 'Price harus berupa angka',
            'ITP_Price.min' => 'Price harus lebih besar atau sama dengan 0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $item = ArcItmMast::where('ARCIM_Code', $arcimCode)->firstOrFail();
        
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

        $createdCount = 0;
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
                            if($margin->TypeofItemCode == 'VIP' || $margin->TypeofItemCode == 'VVIP' || $margin->TypeofItemCode == 'SUITE' || $margin->TypeofItemCode == 'CU'){
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
                            
                            if($margin->TypeofItemCode == 'VIP' || $margin->TypeofItemCode == 'VVIP' || $margin->TypeofItemCode == 'SUITE' || $margin->TypeofItemCode == 'CU'){
                                $priceData['ITP_EpisodeType'] = 'I';
                                $priceData['ITP_ROOMT_Code'] = $margin->TypeofItemCode;
                                $priceData['ITP_ROOMT_Desc'] = $margin->TypeofItemDesc;
                            } else {
                                $priceData['ITP_EpisodeType'] = $margin->TypeofItemCode;
                            }
                        }
                    }
                    
                    ARCItemPriceItaly::create($priceData);
                    $createdCount++;
                }
            } else {
                $baseData['ITP_Price'] = (float) $request->ITP_Price;
                $baseData['ITP_EpisodeType'] = 'O';
                ARCItemPriceItaly::create($baseData);
                $createdCount = 1;
            }
        } else {
            $baseData['ITP_EpisodeType'] = 'O';
            ARCItemPriceItaly::create($baseData);
            $createdCount = 1;
        }

        $message = $createdCount > 1 
            ? "Data berhasil ditambahkan ({$createdCount} record)" 
            : 'Data berhasil ditambahkan';

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
        $validator = Validator::make($request->all(), [
            'ITP_DateFrom' => 'required|date',
            'ITP_Price' => 'required|numeric|min:0',
        ], [
            'ITP_DateFrom.required' => 'Date From wajib diisi',
            'ITP_DateFrom.date' => 'Date From harus berupa tanggal yang valid',
            'ITP_Price.required' => 'Price wajib diisi',
            'ITP_Price.numeric' => 'Price harus berupa angka',
            'ITP_Price.min' => 'Price harus lebih besar atau sama dengan 0',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $price = ARCItemPriceItaly::findOrFail($id);
        
        $updateData = $request->only(['ITP_DateFrom', 'ITP_DateTo', 'ITP_Price']);

        $price->update($updateData);

        $redirectUrl = route('arc-item-price-italy.manage', $arcimCode);
        if ($request->has('status') && $request->status != '') {
            $redirectUrl .= '?status=' . $request->status;
        }

        return redirect($redirectUrl)->with('success', 'Data berhasil diupdate');
    }
}
