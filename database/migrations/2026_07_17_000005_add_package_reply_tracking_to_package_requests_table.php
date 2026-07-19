<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('package_requests', function (Blueprint $table) {
            $table->text('reply_message')->nullable()->after('message');
            $table->timestamp('replied_at')->nullable()->after('reply_message');
            $table->string('email_status', 32)->default('not_sent')->after('seen');
        });
    }

    public function down()
    {
        Schema::table('package_requests', function (Blueprint $table) {
            $table->dropColumn(['reply_message', 'replied_at', 'email_status']);
        });
    }
};
