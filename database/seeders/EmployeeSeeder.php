<?php

namespace Database\Seeders;

use App\Enums\Gender;
use App\Models\Employee;
use App\Models\EmployeeType;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $typeTetap = EmployeeType::where('name', 'Staff Tetap')->first();
        $typeKontrak = EmployeeType::where('name', 'Karyawan Kontrak')->first();
        $typeMagang = EmployeeType::where('name', 'Peserta Magang')->first();

        $accounts = [
            [
                'name' => 'Superadmin System',
                'username' => 'superadmin',
                'role' => 'Superadmin',
                'type_id' => $typeTetap->id ?? 1,
                'nip' => 'SA-001',
                'gender' => Gender::PRIA->value,
                'email' => 'superadmin@jmc.id'
            ],
            [
                'name' => 'Manager HRD',
                'username' => 'managerhrd',
                'role' => 'Manager HRD',
                'type_id' => $typeTetap->id ?? 1,
                'nip' => 'MGR-001',
                'gender' => Gender::PRIA->value,
                'email' => 'managerhrd@jmc.co.id'
            ],
            [
                'name' => 'Admin HRD',
                'username' => 'adminhrd',
                'role' => 'Admin HRD',
                'type_id' => $typeKontrak->id ?? 2,
                'nip' => 'ADM-001',
                'gender' => Gender::WANITA->value,
                'email' => 'adminhrd@jmc.co.id'
            ],
            [
                'name' => 'Staf Karyawan',
                'username' => 'karyawan',
                'role' => 'Karyawan',
                'type_id' => $typeMagang->id ?? 3,
                'nip' => 'STF-001',
                'gender' => Gender::PRIA->value,
                'email' => 'karyawan@jmc.co.id'
            ],
        ];

        foreach ($accounts as $acc) {
            $employee = Employee::create([
                'nip' => $acc['nip'],
                'name' => $acc['name'],
                'email' => $acc['email'] ?? ($acc['username'] . '@jmc.co.id'),
                'gender' => $acc['gender'],
                'join_date' => now()->subYear(),
                'employee_type_id' => $acc['type_id'],
                'is_active' => true,
            ]);

            $user = User::create([
                'employee_id' => $employee->id,
                'username' => $acc['username'],
                'email' => $employee->email,
                'password' => Hash::make($acc['username'] == 'superadmin' ? 'Password123!' : 'password123'),
                'is_active' => true,
            ]);

            $user->assignRole($acc['role']);
        }
    }
}
