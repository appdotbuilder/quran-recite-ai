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
        Schema::create('surahs', function (Blueprint $table) {
            $table->id();
            $table->integer('number')->comment('Surah number (1-114)');
            $table->string('name_arabic')->comment('Arabic name of the surah');
            $table->string('name_english')->comment('English name of the surah');
            $table->string('name_indonesian')->comment('Indonesian name of the surah');
            $table->integer('verses_count')->comment('Number of verses in the surah');
            $table->enum('revelation_type', ['meccan', 'medinan'])->comment('Place of revelation');
            $table->text('description_english')->nullable()->comment('English description');
            $table->text('description_indonesian')->nullable()->comment('Indonesian description');
            $table->timestamps();
            
            $table->index('number');
            $table->index('revelation_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surahs');
    }
};