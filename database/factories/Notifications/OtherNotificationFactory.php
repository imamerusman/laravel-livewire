<?php

namespace Database\Factories\Notifications;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class OtherNotificationFactory extends Factory
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
            'enabled' => $this->faker->boolean,
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'type' => array_rand([
                'abandoned_cart' => 'abandoned_cart',
                'recent_product' => 'recent_product',
                'shopping_time' => 'shopping_time',
                'app_termination' => 'app_termination',
            ]),
            'meta_data' => $this->faker->text,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
