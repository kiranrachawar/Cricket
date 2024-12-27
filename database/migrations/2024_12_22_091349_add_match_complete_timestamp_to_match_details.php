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
        Schema::table('match_details', function (Blueprint $table) {
            $table->timestamp('match_complete_timestamp')->nullable();
            $table->timestamp('match_description')->nullable();
            $table->timestamp('match_format')->nullable();
            $table->timestamp('match_start_timestamp')->nullable();
        });
    }

    public function down()
    {
        Schema::table('match_details', function (Blueprint $table) {
            $table->dropColumn('match_complete_timestamp');
            $table->dropColumn('match_description');
            $table->dropColumn('match_format');
            $table->dropColumn('match_start_timestamp');
        });
    }
};
