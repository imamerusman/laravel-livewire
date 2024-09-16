<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('app_dev_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->foreignId('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->enum('status', [
                'pending',
                'in_progress',
                'completed',
                'cancelled',
                'withdrew'
            ])->default('pending');

            $table->boolean('developer_accounts_created')->default(false);

            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();

            $table->text('feedback')->nullable();


            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_dev_orders');
    }
};
