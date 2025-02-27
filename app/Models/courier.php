<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class courier extends Model
{
    protected $table = "couriers";

    protected $fillable = [
        'name',
        'service',
        'cost',
    ];
}
