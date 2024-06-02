<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Models\{Educator, Identity};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Educator>
 */
class EducatorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'identity_id' => Identity::factory(),
        ];
    }
}
