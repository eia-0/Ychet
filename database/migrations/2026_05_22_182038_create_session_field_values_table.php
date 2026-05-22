<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('session_field_values', function (Blueprint $table) {
        $table->id();
        $table->foreignId('client_session_id')->constrained('client_sessions')->onDelete('cascade');
        $table->foreignId('template_field_id')->constrained('template_fields')->onDelete('cascade');
        $table->text('value')->nullable();
        $table->timestamps();
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('session_field_values');
    }
};
