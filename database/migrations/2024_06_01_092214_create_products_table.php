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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('name_ar')->unique();
            $table->string('name_en')->unique();
            $table->string('description_ar')->nullable()->default(null);
            $table->string('description_en')->nullable()->default(null);
            $table->foreignId('manufacturer_id')
                ->constrained('manufacturers')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('price');
            $table->foreignId('subcategory_id')
                ->constrained('sub_categories')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->string('barcode')->nullable()->default(null);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
