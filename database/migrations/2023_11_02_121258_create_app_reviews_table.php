<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * This migration will create the app_reviews table.
 * That will use to track user submitting ticket like a review.
 * Which can be tracked by the admin.
 * And there will be feedback from the admin to the user.
 * */
return new class extends Migration {
    public function up(): void
    {
        Schema::create('app_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->string('title');
            $table->text('description');

            $table->text('feedback')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'approved', 'rejected','withdrew'])->default('pending');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_reviews');
    }
};
