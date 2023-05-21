<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('android_app', function (Blueprint $table) {
            $table->id();
            $table->string('version');
            $table->string('description');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('android_app');
    }
};
