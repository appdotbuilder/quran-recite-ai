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
        Schema::create('qaris', function (Blueprint $table) {
            $table->id();
            $table->string('name')->comment('Name of the Qari');
            $table->string('name_arabic')->nullable()->comment('Arabic name of the Qari');
            $table->string('country')->nullable()->comment('Country of origin');
            $table->text('description')->nullable()->comment('Description of the Qari');
            $table->string('audio_base_url')->comment('Base URL for audio files');
            $table->boolean('is_featured')->default(false)->comment('Featured Qari status');
            $table->timestamps();
            
            $table->index('is_featured');
            $table->index('country');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('qaris');
    }
};