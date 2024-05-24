<?php

namespace Database\Factories;

use App\Core\Models\Institution;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Institution>
 */
final class InstitutionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            "name" => "University of " . fake()->city(),
            "website" => fake()->optional()->url(),
            "parent_institution_id" => Institution::factory()
        ];
    }
}
