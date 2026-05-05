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
        Schema::create('shopping_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('list_id')->constrained('shopping_lists')->cascadeOnDelete();
            $table->foreignId('ingredient_id')->nullable()->constrained('ingredients')->nullOnDelete();
            $table->string('name');
            $table->string('quantity')->nullable();
            $table->string('unit')->nullable();
            $table->decimal('price_estimate', 8, 2)->nullable();
            $table->string('category')->nullable();
            $table->boolean('is_checked')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shopping_list_items');
    }
};
