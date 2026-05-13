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
        Schema::create('ingredients', function (Blueprint $table) {
            $table->id();
            $table->string('canonical_name');
            $table->string('name_en')->nullable();
            $table->decimal('gram_per_unit', 8, 2)->nullable();
            $table->enum('category', ['zuivel', 'groenten', 'vlees', 'vis', 'granen', 'kruiden', 'specerijen', 'peulvruchten', 'fruit', 'sauzen', 'diepvries', 'blik', 'dranken']);
            $table->decimal('kcal_per_100g',      8, 2)->nullable();
            $table->decimal('protein_per_100g',   8, 2)->nullable();
            $table->decimal('carbs_per_100g',     8, 2)->nullable();
            $table->decimal('fat_per_100g',       8, 2)->nullable();
            $table->decimal('fiber_per_100g',     8, 2)->nullable();
            $table->decimal('sugar_per_100g',     8, 2)->nullable();
            $table->decimal('sodium_per_100g',    8, 2)->nullable();
            $table->decimal('potassium_per_100g', 8, 2)->nullable();
            $table->decimal('iron_per_100g',      8, 2)->nullable();
            $table->decimal('vitamin_a_per_100g', 8, 2)->nullable();
            $table->decimal('vitamin_c_per_100g', 8, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingredients');
    }
};
