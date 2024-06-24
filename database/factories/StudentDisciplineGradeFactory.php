<?php
declare(strict_types=1);

namespace Database\Factories;

use App\Core\Models\Discipline;
use App\Core\Models\Educator;
use App\Core\Models\StudentDisciplineGrade;
use App\Core\Models\StudentGroup;
use Carbon\Carbon;
use Carbon\Exceptions\InvalidFormatException;
use Illuminate\Database\Eloquent\Factories\Factory;
use InvalidArgumentException;

/**
 * @extends Factory<StudentDisciplineGrade>
 */
final class StudentDisciplineGradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @throws InvalidFormatException
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $awardedPointsGenerator = function (array $attributes): float {
            if (!isset($attributes['maximum_points'])) {
                throw new InvalidArgumentException(
                    'maximum_points is required',
                );
            }

            return fake()->randomFloat(
                min: 1,
                max: $attributes['maximum_points'],
            );
        };

        return [
            'student_group_id' => StudentGroup::factory(),
            'discipline_id' => Discipline::factory(),
            'awarded_points' => $awardedPointsGenerator,
            'maximum_points' => fake()->randomFloat(),
            'notes' => fake()->optional()->text(),
            'educator_id' => Educator::factory(),
            'awarded_at' => new Carbon(),
        ];
    }
}
