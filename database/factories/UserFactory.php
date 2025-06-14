<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
    public function manager(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'mobile' => $this->faker->unique()->numerify('01#########'),
            'national_id' => $this->faker->unique()->numerify('##############'),
            'avatar_img' => $this->faker->imageUrl(300, 300, 'people'),
            'country' => $this->faker->countryCode,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'is_approved' => true,
            'created_by' => null,
            'approved_by' => null,
            'banned_at' => null,
            'last_login_in' => now(),
            'role' => 'manager'
        ]);
    }

    public function receptionist(): static
    {
        return $this->state(fn (array $attributes) => [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
            'mobile' => $this->faker->unique()->numerify('01#########'),
            'national_id' => $this->faker->unique()->numerify('##############'),
            'avatar_img' => $this->faker->imageUrl(300, 300, 'people'),
            'country' => $this->faker->countryCode,
            'gender' => $this->faker->randomElement(['male', 'female']),
            'is_approved' => true,
            'created_by' => auth()->id(),
            'approved_by' => null,
            'banned_at' => null,
            'last_login_in' => now(),
            'role' => 'receptionist'
        ]);
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
