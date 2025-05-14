<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['room_id', 'guest_name', 'contact_details', 'check_in', 'check_out', 'special_requests', 'total_amount',];

    protected $casts = [
        'contact_details' => 'array',
    ];

     public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
