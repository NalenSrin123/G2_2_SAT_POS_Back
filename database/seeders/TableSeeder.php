<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('tables')->insert([
            [
                'table_number' => 1,
                'qr_code' => 'QR1',
                'status' => 'available',
                'created_at' => now(),
            ],
            [
                'table_number' => 2,
                'qr_code' => 'QR2',
                'status' => 'available',
                'created_at' => now(),
            ],
            [
                'table_number' => 3,
                'qr_code' => 'QR3',
                'status' => 'available',
                'created_at' => now(),
            ],
        ]);
    
    }
}
