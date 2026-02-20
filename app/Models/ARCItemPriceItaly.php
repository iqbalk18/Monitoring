<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARCItemPriceItaly extends Model
{
    use HasFactory;

    protected $table = 'tcmon_arc_item_price_italy';

    protected $fillable = [
        'ITP_ARCIM_Code',
        'ITP_ARCIM_Desc',
        'ITP_DateFrom',
        'ITP_DateTo',
        'ITP_TAR_Code',
        'ITP_TAR_Desc',
        'ITP_Price',
        'hna',
        'ITP_CTCUR_Code',
        'ITP_CTCUR_Desc',
        'ITP_ROOMT_Code',
        'ITP_ROOMT_Desc',
        'ITP_HOSP_Code',
        'ITP_HOSP_Desc',
        'ITP_Rank',
        'ITP_EpisodeType',
        'ITP_UrgentRate',
        'batch_id',
    ];

    protected $casts = [
        'ITP_DateFrom' => 'date',
        'ITP_DateTo' => 'date',
        'ITP_Price' => 'decimal:2',
        'hna' => 'decimal:2',
        'ITP_UrgentRate' => 'decimal:2',
    ];
}
