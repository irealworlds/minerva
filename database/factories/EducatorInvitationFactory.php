<?php

namespace Database\Factories;

use App\Core\Models\{EducatorInvitation, Identity, Institution};
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<EducatorInvitation>
 */
class EducatorInvitationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'institution_id' => Institution::factory(),
            'inviter_name' => fake()->name(),
            'inviter_email' => fake()->safeEmail(),
            'inviter_id' => Identity::factory(),
            'expired_at' => fake()->dateTimeBetween('+5 days', '+30 years'),
        ];
    }
}
