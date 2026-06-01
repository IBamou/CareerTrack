<?php

namespace Database\Factories;

use App\Models\Reminder;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReminderFactory extends Factory
{
    protected $model = Reminder::class;

    public function definition(): array
    {
        return [
            'user_id' => fake()->randomElement(User::pluck('id')->toArray() ?: [User::factory()->create()->id]),
            'title' => fake()->sentence(),
            'description' => fake()->paragraph(),
            'remind_at' => fake()->dateTimeBetween('now', '+1 month'),
            'status' => 'pending',
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }
}
