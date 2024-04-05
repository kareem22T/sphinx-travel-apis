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
        Schema::create('hotel_rating', function (Blueprint $table) {
            $table->id();
            $table->integer('hotel_id');
            $table->tinyInteger('staff');
            $table->tinyInteger('facilities');
            $table->tinyInteger('cleanliness');
            $table->tinyInteger('comfort');
            $table->tinyInteger('money');
            $table->tinyInteger('location');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotel_rating');
    }
};
