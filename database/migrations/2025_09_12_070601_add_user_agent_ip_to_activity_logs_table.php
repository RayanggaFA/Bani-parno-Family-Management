<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUserAgentIpToActivityLogsTable extends Migration
{
    public function up()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            if (!Schema::hasColumn('activity_logs', 'user_agent')) {
                $table->text('user_agent')->nullable()->after('description');
            }
            
            if (!Schema::hasColumn('activity_logs', 'ip_address')) {
                $table->string('ip_address', 45)->nullable()->after('user_agent');
            }
        });
    }

    public function down()
    {
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->dropColumn(['user_agent', 'ip_address']);
        });
    }
}