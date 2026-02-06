<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['slug', 'name', 'content', 'is_published'];

    protected $casts = [
        'content' => 'array',
        'is_published' => 'boolean',
    ];
}
