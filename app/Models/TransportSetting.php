<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Traits\OwnedResource;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class TransportSetting extends Model
{
    use OwnedResource, LogsActivity;
    protected $fillable = ['base_fare', 'effective_date', 'created_by'];

    protected function casts(): array
    {
        return [
            'effective_date' => 'date',
            'base_fare' => 'decimal:2',
        ];
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['base_fare', 'effective_date'])
            ->useLogName('TransportSetting')
            ->setDescriptionForEvent(fn(string $eventName) => "Setting transport ini telah {$eventName}");
    }
}
