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
use App\Traits\OwnedResource;

class Employee extends Model
{
    use HasFactory, SoftDeletes, LogsActivity, OwnedResource;

    protected $fillable = [
        'nip', 'name', 'email', 'phone', 'photo', 'gender', 'marital_status',
        'children_count', 'birthdate', 'birthplace', 'join_date',
        'position_id', 'department_id', 'employee_type_id', 'is_active', 'created_by'
    ];

    protected $appends = ['age', 'tenure'];

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
            ->logOnly(['nip', 'name', 'department.name', 'position.name', 'is_active'])
            ->logOnlyDirty()
            ->useLogName('Employee')
            ->setDescriptionForEvent(fn(string $eventName) => "Data pegawai ini telah {$eventName}");
    }

    public function getAgeAttribute(): int
    {
        return $this->birthdate ? $this->birthdate->age : 0;
    }

    public function getTenureAttribute(): int
    {
        return $this->join_date ? \Carbon\Carbon::parse($this->join_date)->diffInYears(now()) : 0;
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

    public function transportAllowances(): HasMany
    {
        return $this->hasMany(TransportAllowance::class);
    }

    public function isPermanent(): bool
    {
        return $this->type?->name === 'Staff Tetap';
    }
}
