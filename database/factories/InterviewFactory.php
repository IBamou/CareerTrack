<?php

namespace Database\Factories;

use App\Models\Interview;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InterviewFactory extends Factory
{
    protected $model = Interview::class;

    public function definition(): array
    {
        return [
            'type' => fake()->randomElement(['Phone', 'Video Call', 'HR', 'Technical', 'On-site']),
            'scheduled_at' => fake()->dateTimeBetween('-1 month', '+1 month'),
            'notes' => fake()->optional(0.6)->sentence(),
            'result' => fake()->optional(0.4)->randomElement(['Passed', 'Rejected', 'Cancelled', 'Rescheduled']),
            'job_application_id' => fake()->randomElement(JobApplication::pluck('id')->toArray() ?: [JobApplication::factory()->create()->id]),
            'user_id' => fake()->randomElement(User::pluck('id')->toArray() ?: [User::factory()->create()->id]),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }

    public function forApplication(JobApplication $application): static
    {
        return $this->state(fn () => [
            'job_application_id' => $application->id,
        ]);
    }
}
