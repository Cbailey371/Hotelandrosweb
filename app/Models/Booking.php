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
        'customer_name',
        'email',
        'country',
        'phone',
        'check_in',
        'check_out',
        'guests',
        'status',
        'message',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
