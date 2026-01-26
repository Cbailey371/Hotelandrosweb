<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $fillable = [
        'name_es',
        'name_en',
        'description_es',
        'description_en',
        'price',
        'tax_percentage',
        'extra_person_charge',
        'capacity',
        'status',
        'amenities',
        'image',
    ];

    protected $casts = [
        'amenities' => 'array',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function galleries()
    {
        return $this->belongsToMany(Gallery::class, 'gallery_room');
    }
}
