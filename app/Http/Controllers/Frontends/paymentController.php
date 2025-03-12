<?php

namespace App\Http\Controllers\Frontends;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function index($paymentId)
    {
        // $payment = Payment::findOrFail($paymentId);
        //cek apakah pembayaran sudah ada atau belum berdasarkan payment_id yang dipilih oleh user yang sedang login
        $payment = Payment::where('id', $paymentId)->where('user_id',Auth::user()->id)->firstOrFail();

        //Cek apakah gambar bukti pembayaran sudah diupload atau belum berdasarkan payment_id yang dipiliholeh user yang sedang login
        if ($payment->image !== null){
            return view('frontends.errors.404');
        }

        return view('frontends.payments.index', compact('payment'));
    }

    public function processPayment($orderId)
    {
        $order = Order::findOrFail($orderId);

        //cek apakah pembayaran sudah ada atau belum berdasarkan order_id yang dipilih
        $existingPayment = Payment::where('order_id', $order->id)->first();
        if($existingPayment) {
            return redirect()->route('payments.index', $existingPayment->id);
        }


        //Membuat data pembayaran
        $payment = new Payment();
        $payment->user_id = Auth::user()->id;
        // $payment->user_id = Auth::id();
        $payment->order_id = $order->id;
        $payment->image = null;
        $payment->save();

        return redirect()->route('payments.index', $payment->id);
    }

    public function confirmPayment(Request $request, $paymentId)
    {
        $request->validate([
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'image.required' => 'Gambar bukti pembayaran harus diisi',
            'image.image' => 'file yang diuploas harus berupa gambar',
            'image.mimes' => 'file yang diupload harus berupa jpeg, png, jpg, gif, svg',
            'image.max' => 'ukuran file maksimal 2MB',
        ]);

        $payment = Payment::findOrFail($paymentId);

        if($request->hasFile('image')) {
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/payments'), $image_name);
            $payment->image = 'payments/' . $image_name;
            $payment->save();
        }

        //update status order menjadi success
        $order = Order::findOrFail($payment->order_id);
        $order->status = 'success';
        $order->save();

        toastr ()
            ->positionClass('toast-top-center')
            ->success('Bukti Pembayaran berhasil diupload');

        //Set session flag payment_success ke true
        session (['payment_success' => true]);

        return redirect()->route('payments.success');
    }
}
