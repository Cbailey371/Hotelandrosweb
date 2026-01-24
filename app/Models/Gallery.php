<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gallery extends Model
{
    protected $fillable = [
        'title_es',
        'title_en',
        'image_path',
        'order',
        'show_in_carousel',
        'carousel_order',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'gallery_room');
    }
}
