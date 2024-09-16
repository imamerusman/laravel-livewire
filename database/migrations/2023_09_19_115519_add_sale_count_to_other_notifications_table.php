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
        Schema::table('other_notifications', function (Blueprint $table) {
            $table->bigInteger('sale_count')->default(0)->after('open_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('other_notifications', function (Blueprint $table) {
            //
        });
    }
};
