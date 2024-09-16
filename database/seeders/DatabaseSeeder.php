<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(PlanSubscriptionSeeder::class);
/*        $this->call(CustomerSeeder::class);
        $this->call(NotificationAnalyticsSeeder::class);
        $this->call(OtherNotificationSeeder::class);
        $this->call(ScheduleNotificationSeeder::class);
        $this->call(ProductAnalyticsSeeder::class);*/
    }
}
