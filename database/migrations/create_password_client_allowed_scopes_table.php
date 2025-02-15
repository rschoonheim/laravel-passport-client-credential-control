<?php

namespace Rschoonheim\LaravelPassportClientCredentialControl\Database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('password_client_allowed_scopes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('oauth_clients')->cascadeOnDelete();
            $table->json('scopes')->default('[]');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_client_allowed_scopes');
    }
};
