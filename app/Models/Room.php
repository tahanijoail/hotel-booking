<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;
      protected $fillable = [
        'hotel_id','room_number','room_type', 'price_per_night', 'status'];

        public function hotel()
    {
        return $this->belongsTo(Hotel::class);
    }
}
