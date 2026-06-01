<?php

namespace Database\Factories;

use App\Enums\JobApplicationStatus;
use App\Enums\JobLocationType;
use App\Models\Company;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class JobApplicationFactory extends Factory
{
    protected $model = JobApplication::class;

    public function definition(): array
    {
        return [
            'job_title' => fake()->jobTitle(),
            'salary_min' => fake()->optional(0.7)->numberBetween(30000, 80000),
            'salary_max' => fake()->optional(0.7)->numberBetween(80001, 200000),
            'currency' => fake()->randomElement(['USD', 'EUR', 'GBP']),
            'benefits' => fake()->optional(0.5)->sentence(),
            'location_type' => fake()->randomElement(JobLocationType::cases())->value,
            'location_city' => fake()->optional(0.7)->city(),
            'links' => fake()->optional(0.4)->randomElement([
                [fake()->url()],
                [fake()->url(), fake()->url()],
            ]),
            'status' => fake()->randomElement(JobApplicationStatus::cases())->value,
            'priority' => fake()->randomElement(['low', 'normal', 'high']),
            'applied_at' => fake()->optional(0.9)->dateTimeBetween('-3 months', 'now'),
            'next_follow_up_at' => fake()->optional(0.5)->dateTimeBetween('now', '+1 month'),
            'notes' => fake()->optional(0.6)->paragraph(),
            'company_id' => fake()->randomElement(Company::pluck('id')->toArray() ?: [Company::factory()->create()->id]),
            'applied_by' => fake()->randomElement(User::pluck('id')->toArray() ?: [User::factory()->create()->id]),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'applied_by' => $user->id,
        ]);
    }

    public function withStatus(JobApplicationStatus $status): static
    {
        return $this->state(fn () => [
            'status' => $status->value,
        ]);
    }
}
