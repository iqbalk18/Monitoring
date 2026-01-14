<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ArcItmMast extends Model
{
    use HasFactory;

    protected $table = 'tcmon_arc_itm_mast';

    protected $fillable = [
        'ARCIM_Code',
        'ARCIM_Desc',
        'ARCIM_ServMaterial',
        'ARCIC_Code',
        'ARCIC_Desc',
        'ORCAT_Code',
        'ORCAT_Desc',
        'ARCSG_Code',
        'ARCSG_Desc',
        'ARCBG_Code',
        'ARCBG_Desc',
        'ARCIM_OrderOnItsOwn',
        'ARCIM_ReorderOnItsOwn',
        'ARCIM_EffDate',
        'ARCIM_EffDateTo',
        'TypeofItemCode',
        'TypeofItemDesc',
    ];

    protected $casts = [
        'ARCIM_EffDate' => 'date',
        'ARCIM_EffDateTo' => 'date',
    ];

    /**
     * Get the prices for the item.
     */
    public function prices()
    {
        return $this->hasMany(ARCItemPriceItaly::class, 'ITP_ARCIM_Code', 'ARCIM_Code');
    }
}
