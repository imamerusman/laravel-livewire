<?php

namespace Database\Factories\Notifications;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notifications\ScheduleNotification>
 */
class ScheduleNotificationFactory extends Factory
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
                return User::factory()->create()->id;
            },
            'title' => $this->faker->sentence,
            'body' => $this->faker->paragraph,
            'scheduled_at' => $this->faker->dateTimeBetween('now', '+1 year'),
            'state' => array_rand([
                'pending'=> 'pending',
                'send' => 'send',
            ]),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
