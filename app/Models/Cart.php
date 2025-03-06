<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $table = "carts";
    protected $fillable = [
        'user_id',
        'product_id',
        'price',
        'weight',
    ];

    //Relasi ke tabel user
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'id');
    }
}
