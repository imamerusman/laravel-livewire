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
        Schema::create('user_app_preferences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('theme')->nullable();
            $table->string('product')->nullable();
            $table->string('drawer')->nullable();
            $table->string('navbar')->nullable();
            $table->string('splash')->nullable();
            $table->string('primary_color')->nullable();
            $table->string('secondary_color')->nullable();
            $table->string('finalize')->nullable();
            $table->string('status')->nullable();
            $table->json('meta_data')->nullable();
            $table->boolean('is_completed')->default(false);
            $table->timestamps();


            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('_user__preferences');
    }
};
