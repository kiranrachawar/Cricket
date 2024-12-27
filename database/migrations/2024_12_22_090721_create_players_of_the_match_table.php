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
        Schema::create('match_players_of_the_match', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->integer('player_id');
            $table->string('player_name');
            $table->string('team_name');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_players_of_the_match');
    }
};
