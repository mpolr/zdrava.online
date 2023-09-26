<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('segments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('strava_segment_id')->nullable();
            $table->char('activity_type', 50)->nullable();
            $table->string('name')->nullable();
            $table->float('distance')->nullable();
            $table->float('total_elevation_gain')->nullable();
            $table->string('start_latlng')->nullable();
            $table->string('end_latlng')->nullable();
            $table->integer('private')->nullable();
            $table->integer('hazardous')->nullable();
            $table->text('polyline')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
