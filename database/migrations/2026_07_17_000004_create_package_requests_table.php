<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255);
            $table->string('phone', 64)->nullable();
            $table->text('message');
            $table->string('package', 255);
            $table->string('status', 32)->default('pending');
            $table->boolean('seen')->default(false)->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_requests');
    }
};
