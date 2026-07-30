<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_requests', function (Blueprint $table) {
            $table->string('company', 255)->nullable()->after('email');
        });
    }

    public function down(): void
    {
        Schema::table('package_requests', function (Blueprint $table) {
            $table->dropColumn('company');
        });
    }
};
