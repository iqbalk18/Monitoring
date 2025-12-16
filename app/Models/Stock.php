<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;
    protected $table = 'stock';

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

    // Relationships
    public function stockSAP()
    {
        return $this->belongsTo(StockSAP::class, 'stocksap_id');
    }

    public function stockTCINCItmLcBt()
    {
        return $this->belongsTo(StockTCINCItmLcBt::class, 'stocktcinc_itmlcbt_id');
    }
}
