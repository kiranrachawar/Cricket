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
        Schema::create('match_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id')->unique(); // Reference to `matches` table
            $table->string('type');
            $table->boolean('complete')->default(false);
            $table->boolean('domestic')->default(false);
            $table->boolean('day_night')->default(false);
            $table->integer('year')->nullable();
            $table->integer('day_number')->nullable();
            $table->string('state')->nullable();
            $table->string('status')->nullable();
            $table->string('toss_winner')->nullable();
            $table->string('decision')->nullable();
            $table->string('winning_team')->nullable();
            $table->integer('winning_margin')->nullable();
            $table->boolean('win_by_runs')->default(false);
            $table->boolean('win_by_innings')->default(false);
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('match_details');
    }
};
