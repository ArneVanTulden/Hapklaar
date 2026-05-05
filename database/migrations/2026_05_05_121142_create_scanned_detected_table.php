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
        Schema::create('scanned_detected', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->constrained('scanner_sessions')->cascadeOnDelete();
            $table->foreignId('ingredients_id')->nullable()->constrained('ingredients')->nullOnDelete();
            $table->string('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('scanned_detected');
    }
};
