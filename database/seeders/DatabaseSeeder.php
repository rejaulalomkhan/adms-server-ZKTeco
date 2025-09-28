<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Optional legacy seeder if table exists
        if (Schema::hasTable('jadwal_sholat')) {
            $this->call([
                JadwalSholatSeeder::class,
            ]);
        }

        $this->call([
            CoreSchedulingSeeder::class,
            RolesAndPermissionsSeeder::class,
        ]);
    }
}
