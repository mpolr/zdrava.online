<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('device_models', function (Blueprint $table) {
            $table->foreignId('device_manufacturers_id')->constrained()->onDelete('cascade');
            $table->bigInteger('model_id', false, true);
            $table->string('description');
            $table->timestamps();

            $table->primary(['device_manufacturers_id','model_id']);
            $table->unique(['device_manufacturers_id','model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_models');
    }
};
