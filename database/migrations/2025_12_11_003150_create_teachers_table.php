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
        Schema::create('tb_teachers', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('position', 100);
            $table->text('qualifications')->nullable();
            $table->text('experience')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('photo')->nullable();
            $table->enum('category', ['principal', 'teacher', 'staff'])->default('teacher');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_teachers');
    }
};
