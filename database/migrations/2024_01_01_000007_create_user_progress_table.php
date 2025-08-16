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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('surah_id')->constrained()->onDelete('cascade');
            $table->integer('verses_completed')->default(0)->comment('Number of verses completed');
            $table->integer('average_accuracy')->default(0)->comment('Average accuracy score');
            $table->integer('total_sessions')->default(0)->comment('Total practice sessions');
            $table->json('weak_areas')->nullable()->comment('Areas needing improvement');
            $table->timestamp('last_practiced_at')->nullable()->comment('Last practice session');
            $table->timestamps();
            
            $table->unique(['user_id', 'surah_id']);
            $table->index('user_id');
            $table->index('last_practiced_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};