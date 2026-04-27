<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use App\Traits\OwnedResource;

class Position extends Model
{
    use HasFactory, LogsActivity, OwnedResource;

    protected $fillable = ['name', 'slug', 'created_by'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug'])
            ->useLogName('Position')
            ->setDescriptionForEvent(fn(string $eventName) => "Jabatan ini telah {$eventName}");
    }
}
