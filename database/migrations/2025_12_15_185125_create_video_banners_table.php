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
        Schema::create('video_banners', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description');
            $table->string('btn_text');
            $table->string('video_mp4')->nullable();
            $table->string('video_webm')->nullable();
            $table->string('photo');
            $table->string('photo_alt');
            $table->string('link')->nullable();
            $table->string('meta_description');

            $table->string('page_title');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('video_banners');
    }
};
