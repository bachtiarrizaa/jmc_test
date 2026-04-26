<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    public $timestamps = false;

    protected $fillable = ['user_id', 'module', 'action', 'description', 'ip_address', 'created_at'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
