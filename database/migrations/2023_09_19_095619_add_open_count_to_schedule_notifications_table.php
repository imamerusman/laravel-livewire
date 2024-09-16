<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('schedule_notifications', function (Blueprint $table) {
            $table->bigInteger('open_count')->default(0)->after('state');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule_notifications', function (Blueprint $table) {
            //
        });
    }
};
