<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ARCItemPriceItaly extends Model
{
    use HasFactory;

    protected $table = 'arc_item_price_italy';

    protected $fillable = [
        'ITP_ARCIM_Code',
        'ITP_ARCIM_Desc',
        'ITP_DateFrom',
        'ITP_DateTo',
        'ITP_TAR_Code',
        'ITP_TAR_Desc',
        'ITP_Price',
        'ITP_CTCUR_Code',
        'ITP_CTCUR_Desc',
        'ITP_ROOMT_Code',
        'ITP_ROOMT_Desc',
        'ITP_HOSP_Code',
        'ITP_HOSP_Desc',
        'ITP_Rank',
        'ITP_EpisodeType',
    ];

    protected $casts = [
        'ITP_DateFrom' => 'date',
        'ITP_DateTo' => 'date',
        'ITP_Price' => 'decimal:2',
    ];
}
