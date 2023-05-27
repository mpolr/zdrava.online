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
            $table->text('type')->default('bicycle');
            $table->text('name')->nullable();
            $table->text('description')->nullable();
            $table->text('creator')->nullable();
            $table->float('distance')->nullable();
            $table->float('avg_speed')->nullable();
            $table->float('avg_pace')->nullable();
            $table->float('min_altitude')->nullable();
            $table->float('max_altitude')->nullable();
            $table->float('elevation_gain')->nullable();
            $table->float('elevation_loss')->nullable();
            $table->dateTimeTz('started_at')->nullable();
            $table->dateTimeTz('finished_at')->nullable();
            $table->integer('duration')->nullable();
            $table->text('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
