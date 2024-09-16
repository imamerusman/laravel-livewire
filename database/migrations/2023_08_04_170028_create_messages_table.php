<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->morphs('sender');  // creates `sender_id` and `sender_type` to represent the model sending the message
            $table->text('content');
            $table->boolean('is_read')->default(false);
            $table->boolean('deleted_from_sender')->default(false);
            $table->boolean('deleted_from_receiver')->default(false);
            $table->foreignId('conversation_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
