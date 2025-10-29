<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    protected $table = 'stock';

    protected $fillable = [
        'materialDocument',
        'movementType',
        'specialStockIndicator',
        'indicator',
        'material',
        'sloc',
        'batch',
        'expiredDate',
        'expiredDateFreeText',
        'qty',
        'uom',
        'qtySku',
        'uomSku',
        'currency',
        'poBasePricePerUnit',
        'poDiscountPerUnit',
        'amountInLocalCurrency',
        'map',
        'taxCode',
        'taxRate',
    ];

    protected $casts = [
        'expiredDate' => 'date',
    ];
}

