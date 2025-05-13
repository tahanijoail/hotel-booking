<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
     protected $fillable = [
        'name',
        'location',
        'description',
        'number_of_rooms',
        'contacts',
    ];
 // ضبط القيمة الافتراضية للحقل contacts
    protected static function booted()
    {
        static::creating(function ($hotel) {
            if (empty($hotel->contacts)) {
                $hotel->contacts = []; // تعيين مصفوفة فارغة إذا كانت contacts فارغة
            }
        });
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
