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
        Schema::table('user_challenge_progress', function (Blueprint $table) {
            Schema::rename('user_challange_progress', 'user_challenge_progress');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_challenge_progress', function (Blueprint $table) {
            Schema::rename('user_challenge_progress', 'user_challange_progress');
        });
    }
};
