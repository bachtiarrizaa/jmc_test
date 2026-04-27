<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;
use App\Traits\OwnedResource;

class Department extends Model
{
    use HasFactory, LogsActivity, OwnedResource;

    protected $fillable = ['name', 'slug', 'created_by'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug'])
            ->useLogName('Department')
            ->setDescriptionForEvent(fn(string $eventName) => "Departemen ini telah {$eventName}");
    }
}
