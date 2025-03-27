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
            $table->point('start_latlng')->nullable();
            $table->point('end_latlng')->nullable();
            $table->integer('kom', false, true)->nullable();
            $table->integer('qom', false, true)->nullable();
            $table->integer('private')->nullable();
            $table->integer('hazardous')->nullable();
            $table->text('polyline')->nullable();
            $table->integer('star_count')->nullable();
            $table->string('country')->nullable();
            $table->string('state', 64)->nullable();
            $table->string('city')->nullable();
            $table->integer('climb_category')->nullable();
            $table->float('average_grade')->nullable();
            $table->float('maximum_grade')->nullable();
            $table->float('elevation_high')->nullable();
            $table->float('elevation_low')->nullable();
            $table->timestamps();
        });

        // Обновляем значения для start_latlng и end_latlng, добавляя SRID = 4326
        DB::statement('UPDATE segments SET start_latlng = ST_GeomFromText(CONCAT("POINT(", ST_X(start_latlng), " ", ST_Y(start_latlng), ")"), 4326) WHERE start_latlng IS NOT NULL');
        DB::statement('UPDATE segments SET end_latlng = ST_GeomFromText(CONCAT("POINT(", ST_X(end_latlng), " ", ST_Y(end_latlng), ")"), 4326) WHERE end_latlng IS NOT NULL');
    }

    public function down(): void
    {
        Schema::dropIfExists('segments');
    }
};
