<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\StocksImport;
use App\Imports\StockSAPImport;
use App\Imports\StockTCINCItmLcBtImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\FormStock;

class ImportController extends Controller
{
    public function showForm()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        //     // 'import_type' => 'required|in:sap,trakcare'
        // ]);
        
        try {
            $importType = $request->input('import_type');
            if ($importType === 'sap') {
                Excel::import(new StockSAPImport, $request->file('file'));
                return redirect()->back()->with('success', 'Data SAP berhasil diimport ke tabel StockSAP!');
            } elseif ($importType === 'trakcare') {
                Excel::import(new StockTCINCItmLcBtImport, $request->file('file'));
                return redirect()->back()->with('success', 'Data TrakCare berhasil diimport ke tabel StockTCINC_ItmLcBt!');
            }
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error saat import: ' . $e->getMessage());
        }
    }


    public function downloadJson()
    {
        $formStocks = FormStock::with('items')->get();

        $result = $formStocks->map(function ($stock) {
            $items = $stock->items->map(function ($item, $index) {
                $valueOrEmpty = fn($value) => $value === null ? "" : $value;

                return [
                    'item' => (string) ($index + 1),
                    'movementType' => $valueOrEmpty($item->movementType),
                    'specialStockIndicator' => $valueOrEmpty($item->specialStockIndicator),
                    'indicator' => $valueOrEmpty($item->indicator),
                    'material' => $valueOrEmpty($item->material),
                    'sloc' => $valueOrEmpty($item->sloc),
                    'batch' => $valueOrEmpty($item->batch),
                    'expiredDate' => $item->expiredDate ? $item->expiredDate->format('Ymd') : "",
                    'expiredDateFreeText' => $valueOrEmpty($item->expiredDateFreeText),
                    'qty' => (string) $valueOrEmpty($item->qty),
                    'uom' => $valueOrEmpty($item->uom),
                    'qtySku' => (string) $valueOrEmpty($item->qtySku),
                    'uomSku' => $valueOrEmpty($item->uomSku),
                    'currency' => $valueOrEmpty($item->currency),
                    'poBasePricePerUnit' => $valueOrEmpty($item->poBasePricePerUnit),
                    'poDiscountPerUnit' => $valueOrEmpty($item->poDiscountPerUnit),
                    'amountInLocalCurrency' => $valueOrEmpty($item->amountInLocalCurrency),
                    'map' => $valueOrEmpty($item->map),
                    'taxCode' => $valueOrEmpty($item->taxCode),
                    'taxRate' => $valueOrEmpty($item->taxRate),
                ];
            });

            return [
                'materialDocument' => $stock->materialDocument ?? "",
                'materialDocumentYear' => $stock->materialDocumentYear ?? "",
                'plant' => $stock->plant ?? "",
                'documentDate' => $stock->documentDate ? $stock->documentDate->format('Ymd') : "",
                'postingDate' => $stock->postingDate ? $stock->postingDate->format('Ymd') : "",
                'goodMovementText' => $stock->goodMovementText ?? "",
                'vendor' => $stock->vendor ?? "",
                'purchaseOrder' => $stock->purchaseOrder ?? "",
                'reservation' => $stock->reservation ?? "",
                'outboundDelivery' => $stock->outboundDelivery ?? "",
                'sapTransactionDate' => $stock->sapTransactionDate ? $stock->sapTransactionDate->format('Ymd') : "",
                'sapTransactionTime' => $stock->sapTransactionTime ? $stock->sapTransactionTime->format('His') : "",
                'user' => $stock->user ?? "",
                'items' => $items,
            ];
        });

        $jsonData = $result->toJson(JSON_PRETTY_PRINT);

        return response()->streamDownload(function () use ($jsonData) {
            echo $jsonData;
        }, 'formstock_with_items.json');
    }
}
