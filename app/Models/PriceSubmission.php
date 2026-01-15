<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PriceSubmission extends Model
{
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
        'status',
        'submitted_by',
        'approved_by',
        'rejection_reason',
        'hna',
        'batch_id'
    ];

    public function submitter()
    {
        return $this->belongsTo(User::class, 'submitted_by');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function item()
    {
        return $this->belongsTo(ArcItmMast::class, 'ITP_ARCIM_Code', 'ARCIM_Code');
    }
}
