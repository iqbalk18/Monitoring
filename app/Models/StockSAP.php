<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSAP extends Model
{
    use HasFactory;

    protected $table = 'tcmon_StockSAP';

    protected $fillable = [
        'Combine_Code',
        'Period_DateTime',
        'Material_Desc',
        'Material_Code',
        'Plant',
        'Storage_Loc',
        'Dfstor_loc_level',
        'Batch_No',
        'BU_Code',
        'Qty',
        'Stock_Segment',
        'Currency',
        'Value_Unrestricted',
        'Transit_Transfer',
        'Valin_Trans_Tfr',
        'Quality_Inspection',
        'Value_in_QualInsp',
        'Restricted_UseStock',
        'Value_Restricted',
        'Blocked',
        'Value_BlockedStock',
        'Returns',
        'Value_RetsBlocked',
    ];

    protected $casts = [
        'Qty' => 'decimal:2',
        'Value_Unrestricted' => 'decimal:2',
        'Transit_Transfer' => 'decimal:2',
        'Valin_Trans_Tfr' => 'decimal:2',
        'Quality_Inspection' => 'decimal:2',
        'Value_in_QualInsp' => 'decimal:2',
        'Restricted_UseStock' => 'decimal:2',
        'Value_Restricted' => 'decimal:2',
        'Blocked' => 'decimal:2',
        'Value_BlockedStock' => 'decimal:2',
        'Returns' => 'decimal:2',
        'Value_RetsBlocked' => 'decimal:2',
    ];
}
