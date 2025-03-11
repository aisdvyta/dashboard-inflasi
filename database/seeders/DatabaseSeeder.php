<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\master_flag;
use App\Models\role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        master_flag::insert([
            ['id' => 0, 'desk_flag' => 'Kelompok'],
            ['id' => 1, 'desk_flag' => 'Subkelompok'],
            ['id' => 2, 'desk_flag' => 'Sub-subkelompok'],
            ['id' => 3, 'desk_flag' => 'Entitas'],
        ]);

        role::insert([
            ['nama_role' => 'Admin Provinsi'],
            ['nama_role' => 'Admin Kabkot'],
        ]);
    }
}
