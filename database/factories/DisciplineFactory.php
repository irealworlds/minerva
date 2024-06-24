<?php

namespace Database\Factories;

use App\Core\Models\Discipline;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Discipline>
 */
final class DisciplineFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(3, asText: true),
            'abbreviation' => Str::upper(fake()->lexify('???')),
        ];
    }
}
