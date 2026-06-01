<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! User::count()) {
            User::create([
                'name' => 'Test user',
                'email' => 'test@gmail.com',
                'password' => bcrypt('password123'),
            ]);
        }
    }
}
