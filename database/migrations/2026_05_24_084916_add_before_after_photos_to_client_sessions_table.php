<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('client_sessions', function (Blueprint $table) {
            $table->string('photo_before')->nullable()->after('photo_path');
            $table->string('photo_after')->nullable()->after('photo_before');
        });
    }

    public function down(): void
    {
        Schema::table('client_sessions', function (Blueprint $table) {
            $table->dropColumn(['photo_before', 'photo_after']);
        });
    }
};