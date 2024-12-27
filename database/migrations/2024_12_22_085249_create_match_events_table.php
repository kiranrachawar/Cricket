<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('match_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('scorecard_id')->constrained('scorecards')->onDelete('cascade'); // Assuming you have a scorecards table
            $table->string('wicket_batsman'); // Batsman name
            $table->integer('wicket_number'); // Wicket number (e.g., 1, 2, 3)
            $table->decimal('wicket_over', 4, 2); // Over number when the wicket fell
            $table->integer('wicket_runs'); // Runs scored by the batsman before being out
            $table->integer('ball_number'); // Ball number when the wicket fell
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_events');
    }
};
