<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'maochanpha@gmail.com'],
            [
                'name' => 'Mao chanpha',
                'password' => Hash::make('chanpha123'),
                'role' => 'admin',
            ]
        );
    }
}
