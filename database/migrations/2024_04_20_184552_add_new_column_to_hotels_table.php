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
        Schema::table('hotels', function (Blueprint $table) {
            $table->integer('avg_rating')->default(0);
            $table->integer('num_of_ratings')->default(0);
            $table->integer('avg_staff_rating')->default(0);
            $table->integer('avg_facilities_rating')->default(0);
            $table->integer('avg_cleanliness_rating')->default(0);
            $table->integer('avg_comfort_rating')->default(0);
            $table->integer('avg_money_rating')->default(0);
            $table->integer('avg_location_rating')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('hotels', function (Blueprint $table) {
            //
        });
    }
};
