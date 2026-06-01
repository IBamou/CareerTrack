<?php

namespace Database\Seeders;

use App\Models\Interview;
use App\Models\User;
use Illuminate\Database\Seeder;

class InterviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Interview::factory()->count(10)->forUser(User::first())->create();
    }
}
