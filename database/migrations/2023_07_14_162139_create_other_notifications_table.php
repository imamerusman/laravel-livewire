<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('other_notifications', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->boolean('enabled')->default(false);
            $table->string('title');
            $table->text('body');
            $table->string('type');
            $table->text('meta_data')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('other_notifications');
    }
};
