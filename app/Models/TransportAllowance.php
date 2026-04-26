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
            'km' => 'decimal:2',
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
}
