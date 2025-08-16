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
        Schema::create('verses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('surah_id')->constrained()->onDelete('cascade');
            $table->integer('verse_number')->comment('Verse number within the surah');
            $table->text('text_arabic')->comment('Arabic text of the verse');
            $table->text('text_english')->nullable()->comment('English translation');
            $table->text('text_indonesian')->nullable()->comment('Indonesian translation');
            $table->text('transliteration')->nullable()->comment('Transliteration of the verse');
            $table->string('audio_url')->nullable()->comment('URL to audio recitation');
            $table->timestamps();
            
            $table->index(['surah_id', 'verse_number']);
            $table->unique(['surah_id', 'verse_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('verses');
    }
};