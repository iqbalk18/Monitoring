<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Margin extends Model
{
    use HasFactory;

    protected $table = 'tcmon_margins';

    protected $fillable = [
        'TypeofItemCode',
        'TypeofItemDesc',
        'Margin',
        'ARCIM_ServMateria',
    ];

    protected $casts = [
        'Margin' => 'decimal:2',
    ];
}
