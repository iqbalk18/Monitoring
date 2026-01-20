<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSAPTc extends Model
{
    protected $table = 'tcmon_stocksaptc';

    protected $fillable = [
        'Period_DateTime',
        'Combine_Code',
        'Material_Desc',
        'Material_Code',
        'Plant',
        'Storage_Loc',
        'Batch_No',
        'BU_Code',
        'QTY_SAP',
        'QTY_TC',
    ];
}
