<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'room_id',
        'number_of_rooms',
        'customer_name',
        'email',
        'country',
        'phone',
        'check_in',
        'check_out',
        'nights',
        'base_price',
        'extra_person_total',
        'tax_amount',
        'total_amount',
        'guests',
        'status',
        'message',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
