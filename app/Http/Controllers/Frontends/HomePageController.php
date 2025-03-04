<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use App\Models\Product;


class HomePageController extends Controller
{
    public function index()
    {

        //menampilkanbanner dengan status show position banner 1 dan 2
        $banners = Banner::where('status', 'show')
        ->whereIn('position', [1, 2])
        ->orderBy('id', 'desc')
        ->get()
        ->groupBy('position')
        ->map(function($group){
            return $group->first();
        });


        $products = Product::where('status', 'show')
        ->orderBy('id','desc')
        ->limit(4 )
        ->get();

        return view('frontends.home', compact('banners','products'));

        // dd($banners, $products);
    }

}
