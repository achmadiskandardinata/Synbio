<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    protected $table = 'banners';

    // protected $guarded = ['id']; //tidak bia dimanipulasi
    protected $fillable = [ //bisa dimanipulasi
        'title',
        'slug',
        'subtitle',
        'description',
        'image',
        'position',
        'status',
    ];
}
