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
        Schema::create('innings_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id'); // Reference to the match
            $table->unsignedBigInteger('team_id'); // Team that played this inning
            $table->integer('runs'); // Total runs scored
            $table->integer('wickets'); // Wickets lost
            $table->integer('overs'); // Overs played (e.g., 50, 20)
            $table->integer('inning_number'); // 1st inning, 2nd inning, etc.
            $table->timestamps();

            // Foreign key constraints
            // $table->foreign('match_id')->references('id')->on('match_details')->onDelete('cascade');
            // $table->foreign('team_id')->references('id')->on('teams')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('innings_scores');
    }
};
