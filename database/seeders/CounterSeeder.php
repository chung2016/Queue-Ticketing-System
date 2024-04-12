<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CounterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('counters')->insertOrIgnore([
            [
                'name' => 'A',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'B',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}
