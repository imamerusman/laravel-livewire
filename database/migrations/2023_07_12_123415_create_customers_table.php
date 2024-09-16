<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('shopify_id')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->string('device_token')->nullable();
            $table->string('device_id')->nullable();
            $table->string('timezone')->nullable();
            // Add additional columns for other customer information if needed

            $table->timestamps();

            // Define foreign key relationship with the users table
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
