<?php

namespace Database\Seeders;

use App\Models\ProductAnalytics;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductAnalyticsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         ProductAnalytics::factory()->count(50)->create();
    }
}
