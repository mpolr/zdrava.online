<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->after('first_name');
            $table->string('nickname')->after('last_name')->nullable();
            $table->boolean('subscribe_news')->after('email');
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('nickname', 'name');
            $table->dropColumn([
                'first_name',
                'last_name',
                'nickname',
                'subscribe_news'
            ]);
            $table->string('name')->after('id');
        });
    }
};
