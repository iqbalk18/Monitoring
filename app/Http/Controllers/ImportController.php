<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ImportService;
use App\Models\FormStock;

class ImportController extends Controller
{
    public function __construct(
        private readonly ImportService $importService
    ) {}

    public function showForm()
    {
        return view('import');
    }

    public function import(Request $request)
    {
        // $request->validate([
        //     'file' => 'required|mimes:xlsx,xls,csv',
        //     'import_type' => 'required|in:sap,trakcare'
        // ]);

        $file = $request->file('file');
        $importType = $request->input('import_type');
        $sessionId = $request->input('session_id', session()->getId());

        // For AJAX request
        if ($request->ajax()) {
            try {
                $result = match ($importType) {
                    'sap' => $this->importService->importSAP($file, $sessionId),
                    'trakcare' => $this->importService->importTrakCare($file, $sessionId),
                    default => ['success' => false, 'message' => 'Invalid import type']
                };

                return response()->json($result);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error: ' . $e->getMessage()
                ], 500);
            }
        }

        // For normal form submission (fallback)
        $result = match ($importType) {
            'sap' => $this->importService->importSAP($file, $sessionId),
            'trakcare' => $this->importService->importTrakCare($file, $sessionId),
            default => ['success' => false, 'message' => 'Invalid import type']
        };

        $status = $result['success'] ? 'success' : 'error';
        
        return redirect()->back()->with($status, $result['message']);
    }

    public function getProgress(Request $request)
    {
        $sessionId = $request->input('session_id', session()->getId());
        $progress = $this->importService->getProgress($sessionId);
        
        return response()->json($progress);
    }

    public function clearProgress(Request $request)
    {
        $sessionId = $request->input('session_id', session()->getId());
        $this->importService->clearProgress($sessionId);
        
        return response()->json(['success' => true]);
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
