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
            $table->string('name');
            $table->unsignedBigInteger('category_id');
            $table->string('slug')->unique();
 $table->boolean('is_special')->default(false);
            $table->decimal('price',15,0);
            $table->decimal('discount',15,0)->nullable();
            $table->text('description');
            $table->string('material');
            $table->string('weight');
            $table->string('dimension');
            $table->string('meta_description');
            $table->string('page_title');

            $table->foreign('category_id')->references('id')->on('categories');
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
