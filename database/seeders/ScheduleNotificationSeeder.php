<?php

namespace Database\Seeders;

use App\Models\Notifications\ScheduleNotification;
use Illuminate\Database\Seeder;

class ScheduleNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       ScheduleNotification::factory()
            ->count(50)
            ->create();
    }
}
