<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Customer>
 */
class CustomerFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'shopify_id' => $this->faker->randomNumber(),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'phone' => $this->faker->phoneNumber,
            'device_token' => $this->faker->uuid,
            'device_id' => $this->faker->uuid,
            'timezone' => $this->faker->timezone,
            // Add additional columns for other customer information if needed
        ];
    }
}
