<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hotel extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'location',
        'description',
        'number_of_rooms',
        'contacts',
    ];

    // إضافة التخصيصات لتخزين بيانات 'contacts' على شكل مصفوفة (array)
    protected $casts = [
        'contacts' => 'array',
    ];

    // ضبط القيم الافتراضية للحقل contacts عند الإنشاء
    protected static function booted()
    {
        static::creating(function ($hotel) {
            if (empty($hotel->contacts)) {
                $hotel->contacts = []; // تعيين مصفوفة فارغة إذا كانت contacts فارغة
            }
        });
    }

    // علاقة مع غرف الفندق
    public function rooms()
    {
        return $this->hasMany(Room::class);
    }
}
