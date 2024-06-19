<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Models\{Institution, StudentGroup};
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;

use function is_string;

/**
 * @extends Factory<StudentGroup>
 */
final class StudentGroupFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->company(),
            'parent_type' => fake()->randomElement([
                Institution::class,
                StudentGroup::class,
            ]),
            'parent_id' => function (array $attributes) {
                if (!isset($attributes['parent_type'])) {
                    throw new Exception('Parent type not set');
                }

                $parentClass = $attributes['parent_type'];

                if (!is_string($parentClass)) {
                    throw new Exception('Parent type is not a string');
                }

                if (!method_exists($parentClass, 'factory')) {
                    throw new Exception(
                        "Factory not found for class $parentClass",
                    );
                }

                return $parentClass::factory();
            },
        ];
    }
}
