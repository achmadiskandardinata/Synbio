<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class successController extends Controller
{
    public function index()
    {
        return view('frontends.payments.sukses');
    }
}
