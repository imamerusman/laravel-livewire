<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_abandonment_events', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('status')->default('unresolved');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cart_abandonment_events');
    }
};
