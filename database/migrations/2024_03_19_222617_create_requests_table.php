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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->text('booking_details');
            $table->integer('user_id');
            $table->boolean('status')->default(1); // 1 = reviewing, 2 = booked, 3 = completed, 4 = not completed
            $table->boolean('seen')->default(0); // 1 = reviewing, 2 = booked, 3 = completed, 4 = not completed
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};
