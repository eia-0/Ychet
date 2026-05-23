<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Удаляем внешний ключ, если он существует (без ошибок)
        DB::statement('ALTER TABLE template_fields DROP FOREIGN KEY IF EXISTS template_fields_user_id_foreign');

        // 2. Удаляем столбец user_id, только если он ещё есть
        if (Schema::hasColumn('template_fields', 'user_id')) {
            Schema::table('template_fields', function (Blueprint $table) {
                $table->dropColumn('user_id');
            });
        }

        // 3. Добавляем template_id, если его ещё нет
        if (!Schema::hasColumn('template_fields', 'template_id')) {
            Schema::table('template_fields', function (Blueprint $table) {
                $table->foreignId('template_id')->after('id')->constrained()->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Откат: удаляем template_id (если есть) и возвращаем user_id
        DB::statement('ALTER TABLE template_fields DROP FOREIGN KEY IF EXISTS template_fields_template_id_foreign');

        if (Schema::hasColumn('template_fields', 'template_id')) {
            Schema::table('template_fields', function (Blueprint $table) {
                $table->dropColumn('template_id');
            });
        }

        if (!Schema::hasColumn('template_fields', 'user_id')) {
            Schema::table('template_fields', function (Blueprint $table) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
            });
        }
    }
};