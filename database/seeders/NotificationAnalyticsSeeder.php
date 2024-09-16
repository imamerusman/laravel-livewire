<?php

namespace Database\Seeders;

use App\Models\NotificationAnalytics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        NotificationAnalytics::factory()
            ->count(50)
            ->create();
    }
}
