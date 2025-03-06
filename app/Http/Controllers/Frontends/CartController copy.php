<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function cart()
    {
        $users = Auth::user()->id;
        $carts = Cart::where('user_id', $users)->with('product')->get();

        //Definisikan jumlah minimal dan maksimal pembelian
        $min = 1;
        $max = 5;
        return view('frontends.carts.index', compact('carts', 'min', 'max'));
    }

    public function addCart(Request $request, $slug)
    {
        //cari produk berdasarkan slug
        $product = Product::where('slug', $slug)->firstOrFail();

        //definisi jumlah minimal dan maksimal pembelian
        $min = 1;
        $max = 5;

        //jumlah default pembelian adalah 1
        $weight = $request->input('weight', $min);

        //cari keranjang berdasarkan user_id dan product_id
        $cart = Cart::where('user_id', Auth::user()->id)
            ->where('product_id', $product->id)->first();

        if ($cart) {
            //cek jika jumlah pembelian lebih dari 5 di detail produk
            if ($cart->weight + $weight > $max) {
                toastr()
                    ->positionClass('toast-top-center')
                    ->error('jumlah pembelian melebihi stok');
                return redirect()->route('products.detail', $product->slug);
            }
            $cart->weight += $weight;
            $cart->weight = $product->weight * $cart->weight;
            $cart->price = $product->price * $cart->weight;
            $cart->save();
        } else {
            //cek jika jumlah pembelian lebih dari 5 di keranjang
            if ($weight > $max) {
                toastr()
                    ->positionClass('toast-top-center')
                    ->error('jumlah pembelian melebihi stok');
                return redirect()->route('products.detail', $product->slug);
            }
            Cart::create([
                'user_id' => Auth::user()->id,
                'product_id' => $product->id,
                'price' => $product->price * $weight,
                'weight' => $product->weight * $weight,
            ]);
        }
        toastr()
            ->positionClass('toast-top-center')
            ->success('produk berhasil ditambahkan ke keranjang');
        return redirect()->route('carts');
    }


    public function cartCount()
    {
        $cart = Cart::where('user_id', Auth::user()->id)->count();
        return response()->json(['count' => $cart]);
    }
    public function updateCart(Request $request, $id)
    {
        //fungsi update keranjang belanja di halaman keranjang berdasarkan id keranjang
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $id)->first()->firstOrFail();

        //fungsi untuk default jumlah pembelian adalah 1 berdasarkan berat produk
        $weight = $request->input('weight', 1);

        $min = 1;
        $max = 5;


        if ($weight > $max) {
            return response()->json([
                'status' => 'error',
                'message' => 'jumlah pembelian melebihi stok',
            ], 400);
        }

        $cart->weight = $weight;
        $cart->weight = $cart->product->weight * $cart->weight;
        $cart->price = $cart->product->price * $cart->weight;
        $cart->save();

        return response()->json([
            'status' => 'success',
            'message' => 'jumlah pembelian berhasil diubah',
            'cart' => $cart,
        ]);
    }

    public function deleteCart(Request $request, $id)
    {
        $cart = Cart::where('user_id', Auth::user()->id)->where('id', $id)->first()->firstOrFail();
        $cart->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'produk berhasil dihapus dari keranjang',
        ]);
    }
}
