<?php

namespace Database\Seeders;

use App\Models\Notifications\OtherNotification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OtherNotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
       OtherNotification::factory()
           ->count(50)
           ->create();
    }
}
