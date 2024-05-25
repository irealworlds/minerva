<?php

namespace Database\Factories;

use App\Core\Models\Identity;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use OverflowException;
use RuntimeException;

/**
 * @extends Factory<Identity>
 */
class IdentityFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     * @throws BindingResolutionException
     * @throws OverflowException
     * @throws RuntimeException
     */
    public function definition(): array
    {
        /** @var Hasher $hasher */
        $hasher = app()->make(Hasher::class);

        return [
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= $hasher->make(fake()->password()),
            'remember_token' => Str::random(10),
        ];
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
