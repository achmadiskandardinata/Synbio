<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'bank_id',
        'courier_id',
        'invoice_number',
        'shipping_address',
        'shipping_cost',
        'total_price',
        'status',
    ];

    //Relasi ke tabel user
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    //relasi ke tabel bank
    public function bank()
    {
        return $this->belongsTo(Bank::class, 'bank_id', 'id');
    }

    //relasi ke tabel courier
    public function courier()
    {
        return $this->belongsTo(Courier::class, 'courier_id', 'id');
    }

    //relasi ke tabel OrderItem
    public function orderItem()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'id');
    }
}
