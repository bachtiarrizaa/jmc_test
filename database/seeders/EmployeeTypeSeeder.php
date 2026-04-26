<?php

namespace Database\Seeders;

use App\Models\EmployeeType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class EmployeeTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'Staff Tetap',
            'Karyawan Kontrak',
            'Peserta Magang'
        ];

        foreach ($types as $type) {
            EmployeeType::create([
                'name' => $type,
                'slug' => Str::slug($type)
            ]);
        }
    }
}
