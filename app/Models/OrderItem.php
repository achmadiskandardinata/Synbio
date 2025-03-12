<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $table = "order_items";

    protected $fillable = [
        'order_id',
        'product_id',
        'price',
        'weight',
    ];

    //relasi ke tabel order
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    //relasi ke tabel product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
