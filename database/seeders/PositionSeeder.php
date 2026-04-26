<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PositionSeeder extends Seeder
{
    public function run(): void
    {
        $positions = ['Staff HRD', 'Manager HRD', 'IT Support', 'Admin', 'Accounting'];
        foreach ($positions as $pos) {
            Position::create(['name' => $pos, 'slug' => Str::slug($pos)]);
        }
    }
}
