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
        Schema::create('tb_news', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->enum('category', ['academic', 'event', 'achievement', 'announcement'])->default('academic');
            $table->string('image')->nullable();
            $table->foreignId('author_id')->nullable()->constrained('tb_users')->onDelete('set null');
            $table->enum('status', ['published', 'draft'])->default('draft');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tb_news');
    }
};
