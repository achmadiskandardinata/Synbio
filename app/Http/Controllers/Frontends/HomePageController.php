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

         //Tampilkan product yang paling banyak di orderberdasarkan product_id. order_id pada table order_item
         $productsOrders = Product::select('products.*')
         ->join('order_items','products.id','=','order_items.product_id')
         ->groupBy('products.id')
         ->orderByRaw('SUM(order_items.weight) DESC')
         //->orderByRaw('COUNT(order_items.product_id)DESC')
         ->limit(4)
         ->get();

         return view('frontends.home', compact('banners','products', 'productsOrders'));

         // dd($banners, $products);

        return view('frontends.home', compact('banners','products'));

        // dd($banners, $products);
    }

}
