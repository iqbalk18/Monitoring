<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TcmonArTracking extends Model
{
    use HasFactory;

    protected $table = 'tcmon_ar_tracking';

    protected $fillable = [
        'invoice_no',
        'status',
        'ref_no',
        'courier_via',
        'tracking_no',
        'sent_date',
        'received_date',
        'paid_on',
        'cancelled_date',
        'due_days',
        'remarks'
    ];
}
