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
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('created_at');
            $table->timestamp('used_at')->nullable()->after('expires_at');
            $table->integer('request_count')->default(0)->after('used_at');
            $table->timestamp('last_requested_at')->nullable()->after('request_count');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('password_reset_tokens', function (Blueprint $table) {
            $table->dropColumn(['expires_at', 'used_at', 'request_count', 'last_requested_at']);
        });
    }
};
