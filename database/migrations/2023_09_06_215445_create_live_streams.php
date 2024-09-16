<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('live_streams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('stream_id')->unique()->nullable();
            $table->string('broadcast_id')->unique()->nullable();
            $table->string('title');
            $table->text('description');
            $table->boolean('made_for_kids')->default(true);
            $table->json('products');
            $table->string('stream_link')->nullable();
            $table->string('stream_url')->nullable();
            $table->string('stream_key')->nullable();
            $table->enum('status', ['testing', 'live', 'completed','scheduled'])->default('scheduled');
            $table->timestamp('start_time')->default(now());
            $table->timestamp('end_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('live_streams');
    }
};
