<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('section_videos', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->string('video_path')->nullable();
            $table->string('poster_path')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('section_videos');
    }
};
