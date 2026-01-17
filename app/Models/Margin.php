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
        'DateFrom',
        'DateTo',
    ];

    protected $casts = [
        'Margin' => 'decimal:2',
        'DateFrom' => 'date',
        'DateTo' => 'date',
    ];

    public function getStatusAttribute()
    {
        $now = now();

        if (!$this->DateFrom) {
            return 'Non Active';
        }

        // Check if current time is before the start date (Start of day)
        if ($now < $this->DateFrom->copy()->startOfDay()) {
            return 'Non Active';
        }

        // If DateTo exists, check if current time is after the end date (End of day)
        if ($this->DateTo) {
            if ($now > $this->DateTo->copy()->endOfDay()) {
                return 'Non Active';
            }
        }

        return 'Active';
    }
}
