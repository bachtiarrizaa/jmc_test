<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\OwnedResource;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class TransportAllowance extends Model
{
    use OwnedResource, LogsActivity;
    protected $fillable = [
        'employee_id', 'setting_id', 'km', 'working_days', 'amount', 'month', 'year', 'created_by'
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

        $roundedKm = round($km, 0, PHP_ROUND_HALF_UP);

        // Distance limits
        if ($roundedKm <= 5) {
            return 0;
        }

        $effectiveKm = min($roundedKm, 25);

        return (float) ($baseFare * $effectiveKm * $workingDays);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['km', 'working_days', 'amount', 'month', 'year'])
            ->useLogName('TransportAllowance')
            ->setDescriptionForEvent(fn(string $eventName) => "Tunjangan transport ini telah {$eventName}");
    }
}
