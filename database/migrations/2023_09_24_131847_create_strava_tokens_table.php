<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('strava_tokens', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('strava_user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->string('access_token');
            $table->string('refresh_token');
            $table->dateTimeTz('expires_at');
            $table->unique(['user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('strava_tokens');
    }
};
