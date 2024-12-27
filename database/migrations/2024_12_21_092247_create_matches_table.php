<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // public function up(): void
    // {
    //     Schema::create('matches', function (Blueprint $table) {
    //         $table->id(); // 'matchId'
    //         $table->unsignedBigInteger('series_id');
    //         $table->string('description'); // 'matchDesc'
    //         $table->string('format'); // 'matchFormat'
    //         $table->timestamp('start_date')->nullable();; // Converted 'startDate'
    //         $table->timestamp('end_date')->nullable();; // Converted 'endDate'
    //         $table->string('status'); // 'status'
    //         $table->unsignedBigInteger('team1_id'); // Reference to teams
    //         $table->unsignedBigInteger('team2_id'); // Reference to teams
    //         $table->unsignedBigInteger('venue_id'); // Reference to venues
    //         $table->timestamps();

    //         // Foreign key constraints
    //         // $table->foreign('series_id')->references('id')->on('cricket_series')->onDelete('cascade');
    //         // $table->foreign('team1_id')->references('id')->on('teams')->onDelete('cascade');
    //         // $table->foreign('team2_id')->references('id')->on('teams')->onDelete('cascade');
    //         // $table->foreign('venue_id')->references('id')->on('venues')->onDelete('cascade');
    //     });
    // }

    // /**
    //  * Reverse the migrations.
    //  */
    // public function down(): void
    // {
    //     Schema::dropIfExists('matches');
    // }
};
