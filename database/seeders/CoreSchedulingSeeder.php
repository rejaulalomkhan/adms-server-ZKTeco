<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CoreSchedulingSeeder extends Seeder
{
    public function run(): void
    {
        // Shifts
        DB::table('shifts')->insertOrIgnore([
            [
                'name' => 'Day Shift',
                'code' => 'DAY',
                'start_time' => '09:00:00',
                'end_time' => '21:00:00',
                'is_overnight' => false,
                'break_minutes' => 60,
                'grace_minutes' => 10,
                'expected_hours' => 12,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Night Shift',
                'code' => 'NIGHT',
                'start_time' => '21:00:00',
                'end_time' => '09:00:00',
                'is_overnight' => true,
                'break_minutes' => 60,
                'grace_minutes' => 10,
                'expected_hours' => 12,
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        // Holidays (example)
        DB::table('holidays')->insertOrIgnore([
            [
                'name' => 'New Year',
                'date' => date('Y-01-01'),
                'is_recurring' => true,
                'area_id' => null,
                'office_id' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}


