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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
             $table->foreignId('room_id')->constrained('rooms')->onDelete('cascade');
            $table->string('guest_name');
            $table->json('contact_details')->nullable(); //  (phone, email, address)
            $table->date('check_in');
            $table->date('check_out');
            $table->text('special_requests')->nullable();
            $table->decimal('total_amount', 8, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
