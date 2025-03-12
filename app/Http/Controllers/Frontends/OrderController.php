<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use App\Models\courier;
use App\Models\Order;
use App\Models\Bank;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;



class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::where("user_id", Auth::user()->id)->get();

        //Define the $orders variable
        $order =  $orders->first();

        //Update total price
        if ($order) {
            $order->total_price = $order->orderItem->sum('price');
            $order->save();
        }
        return view('frontends.orders.index', compact('orders'));
    }

    public function show($orderId)
    {
        // $courierr = Courier::find($orderId)->courier;
        // $banks = Order::find($orderId)->bank;

        // $couriers = Courier::all();
        // $banks = Bank::all();
        // $order = Order::with('orderItem')->findOrFail($orderId);
        // return view('frontends.orders.detail', compact('order','couriers','banks'));

        // dd($order, $courier, $banks);

        // Memngambil data orde berdasarkan id dan user_id yang sedang login
        $order = Order::with('orderItem')->where('id', $orderId)->where('user_id', Auth::user()->id)->first();

        //Cek jika order tidak ditemukan
        if (!$order) {
            return view('frontends.errors.404');
        }

        // cek apakah status ordesuda success maka kita tidak bisa lagi ke halam detail order terseburmelalui URL browser
        if ($order->status == 'SUCCESS') {
            return view('frontends.errors.404');
        }
        $couriers = Courier::all();
        $banks = Bank::all();
        return view('frontends.orders.detail', compact('order','couriers','banks'));
    }

    public function update(Request $request, $orderId)
    {
        $request->validate([
            'courier_id' => 'required|exists:couriers,id',
            'bank_id' => 'required|exists:banks,id',
            'shipping_address' => 'required',
        ],
        [
            'courier_id.required' => 'Pilih kurir pengiriman',
            'courier_id.exists'=> 'Kurir pengiriman tidak ditemukan',
            'bank_id.required' => 'Pilih metode pembayaran',
            'bank_id.exists'=> 'Metode pembayaran tidak ditemukan',
            'shipping_address.required' => 'Alamat pengiriman wajib diisi',
        ]);

        $order = Order::findOrFail($orderId);
        $courier = Courier::findOrFail($request->courier_id);

        //Hitung total_price dari order yang ditambahkan dengan biaya pengiriman cost dari courier yang dipilih
        $shippingCost = $courier->cost;
        // $totalPrice = $order->total_price;
        // $totalShipping = $totalPrice + $shippingCost;

        //Membuat random invoice number
        $length = 20;
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(rand(ord('a'), ord('z')));
        }
        $no_invoice = 'INV'. Str::upper($random);

        $order->update([
            'courier_id' => $request->courier_id,
            'bank_id' => $request->bank_id,
            'shipping_address' => $request->shipping_address,
            'shipping_cost' => $shippingCost,
            // 'total_price' => $totalShipping,
            'invoice_number' => $no_invoice,
            'status' => 'PENDING',
        ]);

        //Hapus semua cart user yang sedang login setelah order berhasil dibuat dan lanjut ke proses pembayaran
        Cart::where('user_id', Auth::user()->id)->delete();

        toastr()
            ->positionClass('toast-top-center')
            ->success('Order berhasil dibuat, silahkan lakukan pembayaran');

    return redirect()->route('payments.process', $order->id);

    }
}
