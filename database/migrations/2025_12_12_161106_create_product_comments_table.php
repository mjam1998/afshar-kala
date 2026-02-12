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
        Schema::create('product_comments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->string('name');
            $table->text('comment');
            $table->text('admin_response')->nullable();
            $table->foreign('product_id')->references('id')->on('products');
            $table->timestamps();
            $table->tinyInteger('status')->default(2);//1 فعال 2 در انتظار تایید 3 غیرفعال
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_comments');
    }
};
