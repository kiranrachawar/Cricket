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
        Schema::table('cricket_series', function (Blueprint $table) {
            // Drop the current id column
            $table->dropColumn('id');
        });

        Schema::table('cricket_series', function (Blueprint $table) {
            // Add an auto-incrementing id column
            $table->bigIncrements('id')->first();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
