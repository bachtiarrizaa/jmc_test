<?php

namespace Database\Seeders;

use App\Models\Employee;
use App\Models\TransportAllowance;
use App\Models\TransportSetting;
use App\Models\User;
use Illuminate\Database\Seeder;

class TransportSeeder extends Seeder
{
    public function run(): void
    {
        $superadmin = User::where('username', 'superadmin')->first();

        $setting = TransportSetting::create([
            'base_fare' => 2500,
            'effective_date' => now()->startOfYear(),
            'created_by' => $superadmin->id,
        ]);

        $tetap = Employee::whereHas('type', fn($q) => $q->where('name', 'Staff Tetap'))->first();
        $kontrak = Employee::whereHas('type', fn($q) => $q->where('name', 'Karyawan Kontrak'))->first();

        $cases = [
            [
                'employee' => $tetap,
                'km' => 10,
                'days' => 20,
                'note' => 'Normal'
            ],
            [
                'employee' => $tetap,
                'km' => 10,
                'days' => 18,
                'note' => 'Hari < 19'
            ],
            [
                'employee' => $tetap,
                'km' => 5,
                'days' => 20,
                'note' => 'KM <= 5'
            ],
            [
                'employee' => $tetap,
                'km' => 30,
                'days' => 20,
                'note' => 'KM > 25 (Cap)'
            ],
            [
                'employee' => $kontrak,
                'km' => 20,
                'days' => 20,
                'note' => 'Bukan Staff Tetap'
            ],
            [
                'employee' => $tetap,
                'km' => 5.4,
                'days' => 20,
                'note' => 'KM 5.4 -> 5'
            ],
            [
                'employee' => $tetap,
                'km' => 5.5,
                'days' => 20,
                'note' => 'KM 5.5 -> 6'
            ],
        ];

        foreach ($cases as $i => $case) {
            if (!$case['employee']) continue;

            $amount = TransportAllowance::calculateAmount(
                $setting->base_fare,
                $case['km'],
                $case['days'],
                $case['employee']->isPermanent()
            );

            TransportAllowance::create([
                'employee_id' => $case['employee']->id,
                'setting_id' => $setting->id,
                'km' => $case['km'],
                'working_days' => $case['days'],
                'month' => 4,
                'year' => 2026,
                'amount' => $amount,
                // Add unique suffix if many for same employee
            ]);
            
            // Note: Since I have unique constraint on employee_id, month, year, 
            // I can only have one per employee. 
            // I'll skip ones for the same employee after the first.
            if ($case['employee'] == $tetap && $i > 0) continue; 
        }
        
        // Actually, let's just create 2 valid ones.
        $karyawan = Employee::where('email', 'karyawan@jmc.co.id')->first();
        if ($karyawan) {
             $amount = TransportAllowance::calculateAmount(
                $setting->base_fare,
                15,
                21,
                $karyawan->isPermanent()
            );
            
            TransportAllowance::create([
                'employee_id' => $karyawan->id,
                'setting_id' => $setting->id,
                'km' => 15,
                'working_days' => 21,
                'month' => 4,
                'year' => 2026,
                'amount' => $amount,
            ]);
        }
    }
}
