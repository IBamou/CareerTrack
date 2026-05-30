<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\JobApplication;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'documentable_type' => 'App\Models\JobApplication',
            'documentable_id' => JobApplication::factory(),
            'name' => fake()->words(3, true).'.'.fake()->fileExtension(),
            'path' => 'documents/'.fake()->uuid().'.'.fake()->fileExtension(),
            'mime_type' => fake()->randomElement(['application/pdf', 'image/png', 'image/jpeg', 'application/msword']),
            'size' => fake()->numberBetween(1000, 5000000),
        ];
    }

    public function forUser(User $user): static
    {
        return $this->state(fn () => [
            'user_id' => $user->id,
        ]);
    }
}
