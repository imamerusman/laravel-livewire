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
        Schema::table('figma_designs', function (Blueprint $table) {
            //
            $table->foreignId('figma_cat_id')->nullable()->constrained('figma_categories')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('figma_designs', function (Blueprint $table) {
            //
        });
    }
};
