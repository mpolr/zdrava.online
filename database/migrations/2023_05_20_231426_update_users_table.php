<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->string('first_name')->after('id')->default('');
            $table->string('last_name')->after('first_name')->default('');
            $table->string('nickname')->after('last_name')->nullable();
            $table->string('photo')->after('nickname')->nullable();
            $table->boolean('subscribe_news')->after('email')->default(true);
            $table->boolean('private')->after('password')->default(true);
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table) {
            $table->renameColumn('nickname', 'name');
            $table->dropColumn([
                'first_name',
                'last_name',
                'nickname',
                'photo',
                'subscribe_news',
                'private',
            ]);
            $table->string('name')->after('id');
        });
    }
};
