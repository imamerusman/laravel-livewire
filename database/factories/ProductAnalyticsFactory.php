<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductAnalytics>
 */
class ProductAnalyticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'shopify_product_id' => $this->faker->sentence,
            'name' => fake()->name,
            'views' => $this->faker->randomNumber(),
            'sales' => $this->faker->randomNumber(),
            'searches' => $this->faker->randomNumber(),
            'created_at' => $this->faker->dateTimeBetween('-1 year',now()),
            'updated_at' => $this->faker->dateTimeBetween('-1 year',now()),
        ];
    }
}
