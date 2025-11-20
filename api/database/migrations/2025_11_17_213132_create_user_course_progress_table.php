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
        Schema::create('user_course_progress', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->deleteOnCascade();
            $table->foreignId('course_id')->constrained()->deleteOnCascade();
            $table->string('status', 20);
            $table->unsignedInteger('progress_percent')->default(0);
            $table->dateTime('last_accessed_at')->nullable();
            $table->unsignedInteger('total_minutes_spent')->default(0);
            $table->dateTime('completed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_course_progress');
    }
};
