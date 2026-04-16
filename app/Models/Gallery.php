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
        'show_in_cafe',
        'cafe_order',
    ];

    public function rooms()
    {
        return $this->belongsToMany(Room::class, 'gallery_room');
    }

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}
