<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportAllowance extends Model
{
    protected $fillable = [
        'employee_id', 'setting_id', 'km', 'working_days', 'amount', 'month', 'year'
    ];

    protected function casts(): array
    {
        return [
            'km' => 'integer',
            'amount' => 'decimal:2',
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function setting(): BelongsTo
    {
        return $this->belongsTo(TransportSetting::class, 'setting_id');
    }

    public static function calculateAmount($baseFare, $km, $workingDays, $isPermanent): float
    {
        if (!$isPermanent || $workingDays < 19) {
            return 0;
        }

        // Rounded KM is same as KM if input is int (following user request "gunakan int saja")
        $roundedKm = (int) $km;

        // Distance limits
        if ($roundedKm <= 5) {
            return 0;
        }

        $effectiveKm = min($roundedKm, 25);

        return (float) ($baseFare * $effectiveKm * $workingDays);
    }
}
