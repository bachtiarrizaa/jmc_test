<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeAddress extends Model
{
    protected $table = 'employee_addresses';

    protected $fillable = ['employee_id', 'district', 'regency', 'province', 'full_address'];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }
}
