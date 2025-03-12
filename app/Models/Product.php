<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = "products";

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'weight',
        'price',
        'status',
    ];

    // Relasi ke tabel cart
    public function carts()
    {
        return $this->hasMany(Product::class,'product_id','id');
    }

    //relasi ke tabel OrderItem
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'id');
    }
}
