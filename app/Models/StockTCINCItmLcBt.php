<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockTCINCItmLcBt extends Model
{
    use HasFactory;

    protected $table = 'tcmon_StockTCINC_ItmLcBt';

    protected $fillable = [
        'Combine_Code',
        'Period_DateTime',
        'INCLB_INCI_Code',
        'INCLB_INCI_Desc',
        'INCLB_INCIB_No',
        'INCLB_INCIB_ExpDate',
        'INCLB_CTLOC_Code',
        'INCLB_CTLOC_Desc',
        'INCLB_PhyQty',
        'CTUOM_Code',
        'CTUOM_Desc',
    ];

    protected $casts = [
        'Period_DateTime' => 'datetime',
        'INCLB_INCIB_ExpDate' => 'date',
        'INCLB_PhyQty' => 'decimal:2',
    ];
}
