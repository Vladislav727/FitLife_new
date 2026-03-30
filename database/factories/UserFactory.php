<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{

    protected $model = User::class;

    public function definition(): array
    {
        return [

            'name' => fake()->name(),
            'username' => fake()->unique()->regexify('[a-zA-Z][a-zA-Z0-9_]{4,14}'),

            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),

            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),

            'bio' => fake()->sentence(),
            'avatar' => fake()->imageUrl(200, 200, 'people'),

            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_admin' => true,
        ]);
    }

    public function withProfile(): static
    {
        return $this->afterCreating(function (User $user) {
            $user->update([
                'bio' => fake()->realText(100),
                'avatar' => fake()->imageUrl(200, 200, 'people'),
            ]);
        });
    }
}
