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
        Schema::create('splash_screens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('figma_design_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('image')->nullable()->default(null);
            $table->text('src')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('splash_screens');
    }
};
