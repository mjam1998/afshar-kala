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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('order_id');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('product_color_id');
            $table->unsignedBigInteger('product_size_id');
            $table->decimal('price',15,0);
            $table->decimal('discount',15,0)->nullable();
            $table->integer('quantity');

            $table->timestamps();
            $table->foreign('order_id')->references('id')->on('orders');
            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('product_color_id')->references('id')->on('product_colors');
            $table->foreign('product_size_id')->references('id')->on('product_sizes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
