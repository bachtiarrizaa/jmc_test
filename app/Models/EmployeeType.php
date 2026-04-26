<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class EmployeeType extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = ['name', 'slug'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'slug'])
            ->useLogName('EmployeeType')
            ->setDescriptionForEvent(fn(string $eventName) => "Tipe Pegawai ini telah {$eventName}");
    }
}
