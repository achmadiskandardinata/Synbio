<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    protected $table = "banks";


    // protected $guarded = ['id'];

    protected $fillable = [
        'bank_name',
        'account_number',
        'account_name',
        'image',
    ];
}
