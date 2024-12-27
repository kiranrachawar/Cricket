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
        Schema::create('cricket_series', function (Blueprint $table) {
            $table->unsignedBigInteger('id')->primary();
            $table->string('date', 50); // Stores series group date
            $table->string('name', 255); // Series name
            $table->bigInteger('start_date'); // Start date in milliseconds
            $table->bigInteger('end_date'); // End date in milliseconds
            $table->boolean('is_fantasy_handbook_enabled')->nullable(); // Optional field
            $table->timestamps(); // created_at and updated_at fields
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cricket_series');
    }
};
