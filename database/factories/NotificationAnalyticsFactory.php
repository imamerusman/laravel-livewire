<?php

namespace Database\Factories;

use App\Models\Notifications\OtherNotification;
use App\Models\Notifications\ScheduleNotification;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\NotificationAnalytics>
 */
class NotificationAnalyticsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => function () {
                return \App\Models\Customer::factory()->create()->id;
            },
            'user_id' => function () {
                return \App\Models\User::factory()->create()->id;
            },
            'notification_type' => $this->faker->randomElement([
                OtherNotification::class,
                ScheduleNotification::class,
            ]),
            'notification_id' => function (array $attributes) {
                return $attributes['notification_type'] === OtherNotification::class
                    ? OtherNotification::factory()->create()->id
                    : ScheduleNotification::factory()->create()->id;
            },
            'status' => array_rand([
                'pending' => 'pending',
                'send' => 'send',
                'failed' => 'failed',
            ]),

            'created_at' => $this->faker->dateTimeBetween('-1 year',now()),
            'updated_at' => now(),
        ];
    }
}
