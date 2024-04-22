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
                'color' => 'linear-gradient(#e54243, #e99830)',
            ],
            [
                'name' => 'B',
                'created_at' => now(),
                'updated_at' => now(),
                'color' => 'linear-gradient(#27b79b, #24eb17);',
            ]
        ]);
    }
}
