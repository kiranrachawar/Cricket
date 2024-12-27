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
        Schema::create('match_tosses', function (Blueprint $table) {
            $table->id('id');
            $table->foreignId('match_id')->constrained('matches')->onDelete('cascade');
            $table->string('toss_winner_name');
            $table->string('decision');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('match_tosses');
    }
};
