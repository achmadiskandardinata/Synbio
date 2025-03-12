<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckoutController extends Controller
{
    public function processCheckout(Request $request)
    {
        //Ambil semua cart item user yang sedang login
        $carts = Cart::where("user_id", Auth::user()->id)->get();

        //Jika cart item kosong, redirect ke halaman cart dengan pesan error
        if ($carts->isEmpty()) {
            toastr()
                ->positionClass('toast-top-center')
                ->error('Keranjang belanja masih kosong, silahkan tambahkan produk terlebih dahulu!');
            return redirect()->route('carts');
        }

        //cek apakah user sudah memiliki order yang belum dibayar
        $order = Order::where('user_id', Auth::user()->id)->where('status', 'process')->first();

        if (!$order) {
            //Membuat order baru jika belum ada orderyang diprocess
            $order = Order::create([
                'user_id' => Auth::user()->id,
                'total_pricee' => 0, //akan diupdatesetelahorder item di create
                'status' => 'process'
            ]);
        }

        //Membuat order item dari cart item
        foreach ($carts as $cart) {
            //Cek apakah product sudah ada di order item
            $existingOrderItem = OrderItem::where('order_id', $order->id)
                ->where('product_id', $cart->product_id)
                ->first();

            if (!$existingOrderItem) {
                //Buat order item baru jika product belum ada di order item
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cart->product_id,
                    'price' => $cart->price,
                    'weight' => $cart->product->weight * $cart->weight,
                ]);
            } else {
                //Update order item jika product sudah ada di order item
                $existingOrderItem->price = $cart->price;
                $existingOrderItem->weight = $cart->weight * $cart->weight;
                $existingOrderItem->save();
            }
        }

        //Hapus order item yang tida ada di cart
        // $orderItems = OrderItem::where('order_id', $order->id)->get();
        // foreach ($orderItems as $orderItem) {
        //     if (!$carts->contains('product_id', $orderItem->product_id)) {
        //         $orderItem->delete();
        //     }
        // }

        //Update total price
        $order->total_price =$order->orderItem->sum('price');
        $order->save();

        //Redirect ke halaman order dengan pesan sukses
        toastr()
        ->positionClass('toast-top-center')
        ->success('Berhasil checkout, silahkan lakukan pembayaran!');
        // return redirect()->route('orders');
        return redirect()->route('orders.detail', $order->id);

    }
}
