<?php
// database/migrations/xxxx_xx_xx_xxxxxx_update_families_table_for_auth.php - CREATE NEW FILE

// ================================================================
// UPDATE MIGRATION UNTUK FAMILIES TABLE
// ================================================================

// database/migrations/xxxx_xx_xx_xxxxxx_update_families_table_for_auth.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('families', function (Blueprint $table) {
            if (!Schema::hasColumn('families', 'username')) {
                $table->string('username')->unique()->after('name');
            }
            if (!Schema::hasColumn('families', 'password')) {
                $table->string('password')->after('username');
            }
            if (!Schema::hasColumn('families', 'remember_token')) {
                $table->rememberToken()->after('description');
            }
            if (!Schema::hasColumn('families', 'created_at')) {
                $table->timestamps();
            }
        });
    }

    public function down()
    {
        Schema::table('families', function (Blueprint $table) {
            $table->dropColumn(['username', 'password', 'remember_token']);
            if (Schema::hasColumn('families', 'created_at')) {
                $table->dropTimestamps();
            }
        });
    }
};