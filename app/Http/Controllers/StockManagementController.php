<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Stock;
use App\Models\StockSAP;
use App\Models\StockTCINCItmLcBt;
use App\Models\FormStock;
use Carbon\Carbon;

class StockManagementController extends Controller
{
    public function index()
    {
        $stocks = Stock::orderBy('created_at', 'desc')->get();
        $formStocks = FormStock::orderBy('created_at', 'desc')->get();
        return view('stock-management', compact('stocks', 'formStocks'));
    }

    public function kalkulasi(Request $request)
    {
        try {
            $request->validate([
                'period_date' => 'required|date'
            ]);

            $periodDate = $request->input('period_date');
            
            $stockSAPs = StockSAP::whereNotNull('Combine_Code')
                ->whereDate('Period_DateTime', $periodDate)
                ->get();
                
            $stockTCs = StockTCINCItmLcBt::whereNotNull('Combine_Code')
                ->whereDate('Period_DateTime', $periodDate)
                ->get();

            $tcGrouped = $stockTCs->groupBy('Combine_Code');
            
            $processedCount = 0;
            $plusCount = 0;
            $minusCount = 0;
            $skippedCount = 0;

            foreach ($stockSAPs as $sap) {
                $combineCode = $sap->Combine_Code;
                
                if ($tcGrouped->has($combineCode)) {
                    $tcData = $tcGrouped->get($combineCode)->first();
                    
                    $sapQty = $sap->Qty ?? 0;
                    $tcQty = $tcData->INCLB_PhyQty ?? 0;
                    $selisih = $sapQty - $tcQty;
                    
                    if ($selisih == 0) {
                        $skippedCount++;
                        continue;
                    }
                    
                    if ($selisih > 0) {
                        $indicator = 'P'; 
                        $qtyToSave = $selisih;
                        $plusCount++;
                    } else {
                        $indicator = 'M';
                        $qtyToSave = abs($selisih);
                        $minusCount++;
                    }
                    
                    $expiredDate = null;
                    if (!empty($tcData->INCLB_INCIB_ExpDate)) {
                        try {
                            $date = Carbon::parse($tcData->INCLB_INCIB_ExpDate);
                            if ($date->year > 0 && $date->year < 9999) {
                                $expiredDate = $date->format('Y-m-d');
                            }
                        } catch (\Exception $e) {
                            $expiredDate = null;
                        }
                    }
                    
                    Stock::create([
                        'stocksap_id' => $sap->id,
                        'stocktcinc_itmlcbt_id' => $tcData->id,
                        'Combine_Code' => $combineCode,
                        'materialDocument' => 'ADJSO'.str_replace('-', '', $periodDate),
                        'movementType' => '201',
                        // 'specialStockIndicator' => $sap->Stock_Segment ?? '',
                        'indicator' => $indicator,
                        // 'material' => $sap->Material_Code ?? '',
                        'sloc' => $tcData->INCLB_CTLOC_Code ?? '',
                        'batch' => $tcData->INCLB_INCI_No ?? '',
                        'expiredDate' => $expiredDate,
                        'expiredDateFreeText' => 'stock adjusment Bulan '.date('F Y', strtotime($periodDate)),
                        'qty' => $qtyToSave,
                        'uom' => $tcData->CTUOM_Code ?? null,
                        'qtySku' => $qtyToSave,
                        'uomSku' => $tcData->CTUOM_Code ?? NULL,
                        'currency' =>   'IDR',
                        'poBasePricePerUnit' => 0,
                        'poDiscountPerUnit' => 0,
                        'amountInLocalCurrency' => 0,
                        'map' => 0,
                        // 'taxCode' => '',
                        // 'taxRate' => '',
                    ]);
                    
                    $processedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'period_date' => date('d/m/Y', strtotime($periodDate)),
                    'total_processed' => $processedCount,
                    'plus_indicator' => $plusCount,
                    'minus_indicator' => $minusCount,
                    'skipped_zero' => $skippedCount,
                    'total_sap_records' => $stockSAPs->count(),
                    'total_tc_records' => $stockTCs->count(),
                ],
                'message' => 'Kalkulasi berhasil! Data telah disimpan ke tabel Stock.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadJson(Request $request)
    {
        try {
            $materialDocument = $request->input('materialDocument');
            
            if ($materialDocument) {
                $formStocks = FormStock::where('materialDocument', $materialDocument)
                    ->orderBy('created_at', 'desc')
                    ->get();
            } else {
                $formStocks = FormStock::orderBy('created_at', 'desc')->get();
            }
            
            $result = [];

            foreach ($formStocks as $formStock) {
                $stocks = Stock::where('materialDocument', $formStock->materialDocument)->get();

                $items = [];
                $itemNumber = 1;
                foreach ($stocks as $stock) {
                    $items[] = [
                        'item' => (string)$itemNumber,
                        'movementType' => $stock->movementType ?? '',
                        'specialStockIndicator' => $stock->specialStockIndicator ?? '',
                        'indicator' => $stock->indicator ?? '',
                        'material' => $stock->material ?? '',
                        'sloc' => $stock->sloc ?? '',
                        'batch' => $stock->batch ?? '',
                        'expiredDate' => $stock->expiredDate ? $stock->expiredDate->format('Ymd') : '',
                        'expiredDateFreeText' => $stock->expiredDateFreeText ?? '',
                        'qty' => $stock->qty ?? '0',
                        'uom' => $stock->uom ?? '',
                        'qtySku' => $stock->qtySku ?? '0',
                        'uomSku' => $stock->uomSku ?? '',
                        'currency' => $stock->currency ?? '',
                        'poBasePricePerUnit' => $stock->poBasePricePerUnit ?? '0',
                        'poDiscountPerUnit' => $stock->poDiscountPerUnit ?? '0',
                        'amountInLocalCurrency' => $stock->amountInLocalCurrency ?? '0',
                        'map' => $stock->map ?? '0',
                        'taxCode' => $stock->taxCode ?? '',
                        'taxRate' => $stock->taxRate ?? '',
                    ];
                    $itemNumber++;
                }

                $formStockData = [
                    'materialDocument' => $formStock->materialDocument ?? '',
                    'materialDocumentYear' => $formStock->materialDocumentYear ?? '',
                    'plant' => $formStock->plant ?? '',
                    'documentDate' => $formStock->documentDate ? $formStock->documentDate->format('Ymd') : '',
                    'postingDate' => $formStock->postingDate ? $formStock->postingDate->format('Ymd') : '',
                    'goodMovementText' => $formStock->goodMovementText ?? '',
                    'vendor' => $formStock->vendor ?? '',
                    'purchaseOrder' => $formStock->purchaseOrder ?? '',
                    'reservation' => $formStock->reservation ?? '',
                    'outboundDelivery' => $formStock->outboundDelivery ?? '',
                    'sapTransactionDate' => $formStock->sapTransactionDate ? $formStock->sapTransactionDate->format('Ymd') : '',
                    'sapTransactionTime' => $formStock->sapTransactionTime ? (method_exists($formStock->sapTransactionTime, 'format') ? $formStock->sapTransactionTime->format('His') : str_replace(':', '', $formStock->sapTransactionTime)) : '',
                    'user' => $formStock->user ?? '',
                    'items' => $items,
                ];

                $result[] = $formStockData;
            }

            $filename = 'formstock_with_items_' . date('YmdHis') . '.json';
            
            return response()->json($result)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadJsonByMaterialDocument(Request $request)
    {
        try {
            $request->validate([
                'materialDocument' => 'required|string'
            ]);

            $materialDocument = $request->input('materialDocument');
            $stocks = Stock::where('materialDocument', $materialDocument)->get();
            
            if ($stocks->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data stock dengan Material Document: ' . $materialDocument
                ], 404);
            }

            $filename = 'stock_' . str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '_', $materialDocument) . '_' . date('YmdHis') . '.json';
            
            return response()->json($stocks)
                ->header('Content-Type', 'application/json')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}

