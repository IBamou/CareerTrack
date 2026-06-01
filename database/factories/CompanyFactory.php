<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class CompanyFactory extends Factory
{
    protected $model = Company::class;

    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'website' => fake()->optional(0.8)->url(),
            'industry' => fake()->optional(0.7)->randomElement([
                'Technology', 'Finance', 'Healthcare', 'Education',
                'Manufacturing', 'Retail', 'Media', 'Consulting',
            ]),
            'location' => fake()->optional(0.7)->city().', '.fake()->country(),
            'notes' => fake()->optional(0.4)->paragraph(),
            'user_id' => fake()->randomElement(User::pluck('id')->toArray() ?: [User::factory()->create()->id]),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }
}
