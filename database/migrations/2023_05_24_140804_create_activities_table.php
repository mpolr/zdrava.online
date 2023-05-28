<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('users_id')->unsigned();
            $table->integer('sport')->default(2);
            $table->integer('sub_sport')->default(7);
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->text('creator')->nullable();
            $table->integer('device_manufacturers_id')->nullable();
            $table->float('distance')->nullable();
            $table->float('avg_speed')->nullable();
            $table->float('max_speed')->nullable();
            $table->float('avg_pace')->nullable();
            $table->float('min_altitude')->nullable();
            $table->float('max_altitude')->nullable();
            $table->float('elevation_gain')->nullable();
            $table->float('elevation_loss')->nullable();
            $table->dateTimeTz('started_at')->nullable();
            $table->dateTimeTz('finished_at')->nullable();
            $table->integer('duration')->nullable();
            $table->integer('duration_total')->nullable();
            $table->integer('avg_heart_rate')->nullable();
            $table->integer('max_heart_rate')->nullable();
            $table->integer('avg_cadence')->nullable();
            $table->integer('max_cadence')->nullable();
            $table->integer('total_calories')->nullable();
            $table->text('file')->nullable();
            $table->text('image')->nullable();
            $table->float('start_position_lat')->nullable();
            $table->float('start_position_long')->nullable();
            $table->float('end_position_lat')->nullable();
            $table->float('end_position_long')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};