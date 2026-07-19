<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email');
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->text('message')->nullable();
            $table->timestamp('booking_utc')->nullable();
            $table->string('booking_local')->nullable();
            $table->string('booking_timezone')->nullable();
            $table->string('status')->default('pending');
            $table->text('admin_notes')->nullable();
            $table->string('meeting_duration')->default('30 minutes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
