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
        // Scorecards Table
        Schema::create('scorecards', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('match_id');
            $table->unsignedBigInteger('bat_team_id');
            $table->unsignedBigInteger('bowl_team_id');
            $table->float('overs', 5, 1);
            $table->integer('runs');
            $table->integer('wickets');
            $table->float('run_rate', 5, 2);
            $table->integer('extras');
            $table->timestamps();

            $table->foreign('match_id')->references('id')->on('matches')->onDelete('cascade');
            $table->foreign('bat_team_id')->references('id')->on('teams')->onDelete('cascade');
            $table->foreign('bowl_team_id')->references('id')->on('teams')->onDelete('cascade');
        });

        // Batsmen Table
        Schema::create('batsmen', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scorecard_id');
            $table->string('name');
            $table->boolean('is_captain')->default(false);
            $table->boolean('is_keeper')->default(false);
            $table->integer('runs');
            $table->integer('balls');
            $table->integer('fours');
            $table->integer('sixes');
            $table->float('strike_rate', 5, 2);
            $table->string('out_desc')->nullable();
            $table->timestamps();

            $table->foreign('scorecard_id')->references('id')->on('scorecards')->onDelete('cascade');
        });

        // Bowlers Table
        Schema::create('bowlers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scorecard_id');
            $table->string('name');
            $table->float('overs', 5, 1);
            $table->integer('maidens');
            $table->integer('runs');
            $table->integer('wickets');
            $table->float('economy', 5, 2);
            $table->timestamps();

            $table->foreign('scorecard_id')->references('id')->on('scorecards')->onDelete('cascade');
        });

        // Partnerships Table
        Schema::create('partnerships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('scorecard_id');
            $table->string('bat1_name');
            $table->string('bat2_name');
            $table->integer('total_runs');
            $table->integer('total_balls');
            $table->timestamps();

            $table->foreign('scorecard_id')->references('id')->on('scorecards')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('partnerships');
        Schema::dropIfExists('bowlers');
        Schema::dropIfExists('batsmen');
        Schema::dropIfExists('scorecards');
    }
};
