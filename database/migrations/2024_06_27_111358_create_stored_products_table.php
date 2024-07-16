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
        Schema::create('stored_products', function (Blueprint $table) {
            $table->id();
            $table->morphs('storable');
            $table->foreignId('product_id')
                ->constrained('products')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->date('expiration_date')
                ->nullable()
                ->default(null);
            $table->unsignedInteger('valid_quantity')->default(0);
            $table->unsignedInteger('expired_quantity')->default(0);
            $table->boolean('active')->default(false);
            $table->unsignedInteger('max')->default(100);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stored_products');
    }
};
