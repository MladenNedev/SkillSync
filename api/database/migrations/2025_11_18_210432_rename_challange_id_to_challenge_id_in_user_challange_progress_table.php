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
        Schema::table('user_challange_progress', function (Blueprint $table) {
            $table->renameColumn('challange_id', 'challenge_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('user_challange_progress', function (Blueprint $table) {
            $table->renameColumn('challenge_id', 'challange_id');
        });
    }
};
