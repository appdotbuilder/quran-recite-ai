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
        Schema::create('recitation_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('surah_id')->constrained()->onDelete('cascade');
            $table->integer('verse_number')->comment('Verse being recited');
            $table->string('audio_file_path')->comment('Path to recorded audio');
            $table->json('ai_feedback')->nullable()->comment('AI analysis results');
            $table->integer('accuracy_score')->nullable()->comment('Accuracy score 0-100');
            $table->json('tajwid_errors')->nullable()->comment('Tajwid errors detected');
            $table->json('pronunciation_errors')->nullable()->comment('Pronunciation errors');
            $table->enum('status', ['pending', 'analyzed', 'reviewed'])->default('pending');
            $table->timestamps();
            
            $table->index(['user_id', 'created_at']);
            $table->index(['surah_id', 'verse_number']);
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recitation_sessions');
    }
};