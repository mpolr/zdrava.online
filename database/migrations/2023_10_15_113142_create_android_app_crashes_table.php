<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('android_app_crashes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('issue_id', 32);
            $table->text('report_id');
            $table->text('app_version_code');
            $table->text('app_version_name');
            $table->text('package_name');
            $table->text('file_path');
            $table->text('phone_model');
            $table->text('android_version');
            $table->text('build');
            $table->text('brand');
            $table->text('product');
            $table->bigInteger('total_mem_size', false, true)->nullable();
            $table->bigInteger('available_mem_size', false, true)->nullable();
            $table->text('custom_data')->nullable();
            $table->text('stack_trace')->nullable();
            $table->text('initial_configuration');
            $table->text('crash_configuration');
            $table->text('display');
            $table->text('user_comment')->nullable();
            $table->text('user_app_start_date');
            $table->text('user_crash_date');
            $table->text('dumpsys_meminfo')->nullable();
            $table->text('logcat')->nullable();
            $table->text('installation_id');
            $table->text('device_features');
            $table->text('environment');
            $table->text('shared_preferences');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('android_app_crashes');
    }
};
