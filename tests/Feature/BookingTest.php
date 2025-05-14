<?php

use App\Models\Hotel;
use App\Models\Room;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('يمكن إنشاء حجز لغرفة متاحة', function () {
    $hotel = Hotel::factory()->create();
    $room = Room::factory()->create([
        'hotel_id' => $hotel->id,
        'status' => 'available',
    ]);

    $data = [
        'room_id' => $room->id,
        'guest_name' => 'أحمد محمد',
        'contact_details' => [
            'phone' => '777123456',
            'email' => 'ahmed@example.com',
            'address' => 'صنعاء، اليمن',
        ],
        'check_in' => now()->addDays(1)->toDateString(),
        'check_out' => now()->addDays(3)->toDateString(),
        'special_requests' => 'قرب المصعد',
        'total_amount' => 300.00,
    ];

    $response = $this->post('/bookings', $data);

    $response->assertStatus(302);
    $this->assertDatabaseHas('bookings', [
        'guest_name' => 'أحمد محمد',
        'room_id' => $room->id,
    ]);
});
