<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create admin user
        User::create([
            'username' => 'agung',
            'password' => Hash::make('password'),
            'role' => 'ADMIN',
        ]);

        // Create regular user
        // User::create([
        //     'username' => 'user',
        //     'password' => Hash::make('user123'),
        //     'role' => 'USER',
        // ]);

        // Create additional test users
        // User::create([
        //     'username' => 'testuser',
        //     'password' => Hash::make('password'),
        //     'role' => 'USER',
        // ]);
    }
}
