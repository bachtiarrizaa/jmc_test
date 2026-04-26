<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransportSetting extends Model
{
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
}
