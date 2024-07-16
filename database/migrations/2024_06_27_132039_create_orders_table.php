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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->morphs('orderable_from');
            $table->morphs('orderable_by');
            $table->foreignId('status_id')
                ->constrained('order_statuses')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->unsignedInteger('order_cost');
            $table->unsignedInteger('shipping_cost')->default(0);
            $table->unsignedInteger('total_cost');
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnUpdate()
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
