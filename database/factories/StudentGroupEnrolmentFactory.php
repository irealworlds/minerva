<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Core\Models\{StudentGroup, StudentGroupEnrolment, StudentRegistration};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentGroupEnrolment>
 */
class StudentGroupEnrolmentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'student_group_id' => StudentGroup::factory(),
            'student_registration_id' => StudentRegistration::factory(),
        ];
    }
}
