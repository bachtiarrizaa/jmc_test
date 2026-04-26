<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Position;
use App\Models\Department;
use App\Models\EmployeeType;

class EmployeeMasterSeeder extends Seeder
{
    public function run(): void
    {
        $positions = ['Manager', 'Staf', 'Magang'];
        foreach ($positions as $pos) {
            Position::firstOrCreate(['name' => $pos], ['slug' => Str::slug($pos)]);
        }

        $departments = ['Marketing', 'HRD', 'Production', 'Executive', 'Commissioner'];
        foreach ($departments as $dept) {
            Department::firstOrCreate(['name' => $dept], ['slug' => Str::slug($dept)]);
        }

        $types = ['Staff Tetap', 'Kontrak', 'Magang'];
        foreach ($types as $type) {
            EmployeeType::firstOrCreate(['name' => $type], ['slug' => Str::slug($type)]);
        }
    }
}
