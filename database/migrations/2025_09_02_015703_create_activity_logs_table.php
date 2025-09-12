<?php
// database/migrations/xxxx_xx_xx_create_activity_logs_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivityLogsTableFixed extends Migration
{
    public function up()
    {
        // Drop existing table if has wrong structure
        Schema::dropIfExists('activity_logs');
        
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->onDelete('cascade');
            $table->string('subject_type');
            $table->unsignedBigInteger('subject_id')->nullable();
            $table->text('description');
            $table->text('user_agent')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();
            
            $table->index(['family_id', 'created_at']);
            $table->index(['subject_type', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('activity_logs');
    }
}