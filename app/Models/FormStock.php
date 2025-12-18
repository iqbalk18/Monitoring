<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FormStock extends Model
{
    use HasFactory;

    protected $table = 'tcmon_formstock';

    protected $fillable = [
        'materialDocument',
        'materialDocumentYear',
        'plant',
        'documentDate',
        'postingDate',
        'goodMovementText',
        'vendor',
        'purchaseOrder',
        'reservation',
        'outboundDelivery',
        'sapTransactionDate',
        'sapTransactionTime',
        'user',
    ];

    protected $casts = [
        'documentDate' => 'date',
        'postingDate' => 'date',
        'sapTransactionDate' => 'date',
        'sapTransactionTime' => 'datetime:H:i:s',
    ];

    public function items()
    {
        return $this->hasMany(StockImport::class, 'materialDocument', 'materialDocument');
    }
}
