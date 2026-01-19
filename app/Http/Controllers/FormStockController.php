<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FormStock;

class FormStockController extends Controller
{
    public function showForm()
    {
        return view('import_excel');
    }

    public function store(Request $request)
    {
        $request->validate([
            'materialDocument' => 'nullable|string',
            'materialDocumentYear' => 'nullable|string',
            'plant' => 'nullable|string',
            'documentDate' => 'nullable|date',
            'postingDate' => 'nullable|date',
            'goodMovementText' => 'nullable|string',
            'vendor' => 'nullable|string',
            'purchaseOrder' => 'nullable|string',
            'reservation' => 'nullable|string',
            'outboundDelivery' => 'nullable|string',
            'sapTransactionDate' => 'nullable|date',
            'sapTransactionTime' => 'nullable',
            'user' => 'nullable|string',
        ]);

        FormStock::create($request->all());

        return redirect()->back()->with('success', 'Data saved successfully!');
    }
}
