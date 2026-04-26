<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DepartmentSeeder extends Seeder
{
    public function run(): void
    {
        $departments = ['Human Resources', 'Information Technology', 'Finance', 'General Affairs'];
        foreach ($departments as $dept) {
            Department::create(['name' => $dept, 'slug' => Str::slug($dept)]);
        }
    }
}
