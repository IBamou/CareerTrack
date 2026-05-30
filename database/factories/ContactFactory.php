<?php

namespace Database\Factories;

use App\Models\Company;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ContactFactory extends Factory
{
    protected $model = Contact::class;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'role' => fake()->optional(0.8)->jobTitle(),
            'email' => fake()->optional(0.7)->safeEmail(),
            'phone' => fake()->optional(0.5)->phoneNumber(),
            'notes' => fake()->optional(0.4)->sentence(),
            'company_id' => Company::factory(),
            'user_id' => User::factory(),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }

    public function forCompany(Company $company): static
    {
        return $this->state(fn () => [
            'company_id' => $company->id,
        ]);
    }
}
