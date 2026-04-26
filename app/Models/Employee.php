<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Models\Concerns\LogsActivity;
use Spatie\Activitylog\Support\LogOptions;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity;

    protected $fillable = [
        'nip', 'name', 'email', 'phone', 'photo', 'gender', 'marital_status',
        'children_count', 'birthdate', 'birthplace', 'join_date',
        'position_id', 'department_id', 'employee_type_id', 'is_active'
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
            'join_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['nip', 'name', 'department_id', 'position_id'])
            ->useLogName('Employee')
            ->setDescriptionForEvent(fn(string $eventName) => "Data pegawai ini telah {$eventName}");
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }

    public function educations(): HasMany
    {
        return $this->hasMany(EmployeeEducation::class);
    }

    public function address(): HasOne
    {
        return $this->hasOne(EmployeeAddress::class);
    }
}
