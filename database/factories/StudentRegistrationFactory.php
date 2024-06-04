<?php

namespace Database\Factories;

use App\Core\Models\{Identity, StudentRegistration};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<StudentRegistration>
 */
class StudentRegistrationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array{
     *     identity_id: IdentityFactory
     * }
     */
    public function definition(): array
    {
        return [
            'identity_id' => Identity::factory(),
        ];
    }
}
