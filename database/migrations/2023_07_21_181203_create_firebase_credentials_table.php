<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('firebase_credentials', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->text('type');
            $table->string('project_id');
            $table->string('private_key_id');
            $table->text('private_key');
            $table->string('client_email');
            $table->string('client_id');
            $table->string('auth_uri');
            $table->string('token_uri');
            $table->string('auth_provider_x509_cert_url');
            $table->string('client_x509_cert_url');
            $table->timestamp('last_used_at')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('firebase_credentials');
    }
};
