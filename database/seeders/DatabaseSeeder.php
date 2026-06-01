<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UserSeeder::class,
            CompanySeeder::class,
            JobApplicationSeeder::class,
            InterviewSeeder::class,
            ContactSeeder::class,
            TagSeeder::class,
            TaggableSeeder::class,
        ]);
    }
}
