<?php
// database/migrations/xxxx_xx_xx_create_members_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();
            $table->foreignId('family_id')->constrained()->cascadeOnDelete();
            $table->string('full_name');
            $table->string('profile_photo')->nullable();
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('occupation')->nullable();
            $table->string('phone_number')->nullable();
            $table->string('email')->nullable();
            $table->enum('gender', ['Laki-laki', 'Perempuan']);
            $table->enum('status', ['Belum Menikah', 'Sudah Menikah', 'Janda/Duda', 'Memilih untuk tidak menjawab']);
            $table->integer('generation');
            $table->foreignId('parent_id')->nullable()->constrained('members')->nullOnDelete();
            $table->string('domicile_city');
            $table->string('domicile_province');
            $table->text('ktp_address');
            $table->text('current_address');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};