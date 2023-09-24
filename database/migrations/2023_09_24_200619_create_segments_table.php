<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('strava_user_id')->nullable();
            $table->string('name');
            $table->float('distance');
            $table->float('total_elevation_gain');
            $table->string('start_latlng');
            $table->string('end_latlng');
            $table->integer('private')->default(0);
            $table->integer('hazardous')->default(0);
            $table->string('polyline');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
