<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class ProductPageController extends Controller
{
    public function index()
    {
        $products = Product::latest()->when(
            request()->q,
            function ($products) {
                    $products = $products->where('title', 'like', '%' . request()->q . '%');
                })->orderBy('id', 'desc')->paginate(10);

        return view('frontends.products.index', compact('products'));
    }

    public function show($slug)
    {
        $product = Product::where('slug', $slug)->first();
        $products = Product::where('id','!=',$product->id)->orderBy('id', 'desc')->paginate(4);

        //Definisikan jumla minimal dan maksimal pembelian produk
        $min = 1;
        $max = 5;

        return view('frontends.products.detail', compact('product', 'products', 'min', 'max'));
    }
}
