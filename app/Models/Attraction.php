<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    protected $fillable = [
        'title_es',
        'title_en',
        'description_es',
        'description_en',
        'image_path',
        'order',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute()
    {
        return $this->image_path ? asset($this->image_path) : null;
    }
}
