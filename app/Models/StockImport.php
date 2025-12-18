<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockImport extends Model
{
    protected $table = 'tcmon_stock';

    protected $fillable = [
        'stocktcinc_itmlcbt_id',
        'stocksap_id',
        'Combine_Code',
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

