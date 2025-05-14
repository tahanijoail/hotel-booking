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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
             $table->foreignId('hotel_id')->constrained('hotels')->onDelete('cascade');
            $table->string('room_number');
            $table->string('room_type'); // room type (single, double, suite, etc.)
            $table->decimal('price_per_night', 8, 2);
            $table->enum('status', ['available', 'booked', 'under maintenance']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};

