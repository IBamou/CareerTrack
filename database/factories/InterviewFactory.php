<?php

namespace Database\Factories;

use App\Models\Interview;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Interview>
 */
class InterviewFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['Phone', 'Video Call', 'HR', 'Technical', 'On-site']),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'notes' => fake()->optional()->sentence(),
            'result' => fake()->optional()->randomElement(['Passed', 'Rejected', 'Cancelled', 'Rescheduled']),
        ];
    }
}
