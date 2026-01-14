<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = [
            [
                'username' => 'widy',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'PRICE_APPROVER',
            ],
            [
                'username' => 'iqbal',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'PRICE_ENTRY',
            ],
            [
                'username' => 'windy',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'PRICE_STRATEGY',
            ],
        ];

        foreach ($users as $userData) {
            \App\Models\User::updateOrCreate(
                ['username' => $userData['username']],
                $userData
            );
        }
    }
}
