<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->date('booking_date')->nullable()->after('booking_utc');
            $table->string('booking_time')->nullable()->after('booking_date');
            $table->string('service')->nullable()->after('booking_time');
            $table->text('notes')->nullable()->after('service');
            $table->string('rescheduled_from')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['booking_date', 'booking_time', 'service', 'notes', 'rescheduled_from']);
        });
    }
};
