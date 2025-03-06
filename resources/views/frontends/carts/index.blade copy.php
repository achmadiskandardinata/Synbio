@extends('frontends.layouts.app', ['title' => 'Carts'])

@section('content')
    <section id="keranjang">
        <div class="container">
            @if ($carts->isEmpty())
                <div class="d-flex justify-content-center">
                    <img src="{{ asset('frontends/assets/empty-cart.png') }}" alt="Keranjang Kosong" class="img-fluid">
                </div>
            @else
                <div class="row">
                    <div class="col">
                        <h3>Keranjang</h3>
                    </div>
                </div>

                <form action="#">
                    <div class="row mt-3">
                        <div class="col-lg-8">
                            @foreach ($carts as $cart)
                                <div class="row cart-item" data-id="{{ $cart->id }}">
                                    <div class="col">
                                        <div class="card mb-3">
                                            <div class="row g-0">
                                                <div class="col-md-4 foto-produk">
                                                    <img src="{{ asset("storage/products/{$cart->product->image}") }}"
                                                        alt="{{ $cart->product->title }}" class="img-fluid rounded-start"
                                                        alt="{{ $cart->product->title }}">
                                                </div>
                                                <div class="col-md-8">
                                                    <div class="card-body">
                                                        <h5 class="card-title">{{ $cart->product->title }}</h5>
                                                        <div class="row mt-5">
                                                            <div class="col-lg-4">
                                                                <label for="jumlah" class="form-label">Jumlah</label>
                                                                <div class="d-flex">
                                                                    <input type="number" class="form-control me-2 weight"
                                                                        value="{{ $cart->weight }}"
                                                                        min="{{ $min }}" max="{{ $max }}">
                                                                    <h6>kg</h6>
                                                                </div>
                                                            </div>

                                                            <div class="col-lg-4">
                                                                <label for="harga" class="form-label">Harga/kg</label>
                                                                <h5 id="item-price">
                                                                    {{ moneyFormat($cart->price / $cart->weight) }}</h6>
                                                            </div>

                                                            <div class="col-lg-4 text-end">
                                                                <button class="btn btn-danger delete-cart"
                                                                    data-id="{{ $cart->id }}">Hapus</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="col-lg-4">
                            <div class="card p-3">
                                <div class="card-body">
                                    <h3>Ringkasan Belanja</h3>
                                    <h6 id="total-weigth">Jumlah : {{ $carts->sum('weight') }} kg</h6>
                                    <h4 id="total-price">Total : {{ moneyFormat($carts->sum('price')) }}</h4>

                                    <button type="submit" class="btn btn-success w-100 mt-3">Lanjutkan ke
                                        Pembayaran</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
        </div>
        @endif

    </section>
@endsection

@push('js')
   <script>
     $(document).ready(function() {
            //Update sumary berdasarkan weight yang diupdate
            function updateSummary() {
                var totalWeight = 0;
                var totalPrice = 0;

                //Looping semua item di keranjang
                $('.cart-item').each(function() {
                    var weight = parseInt($(this).find('.weight').val());
                    var price = parseInt($(this).find('.item-price').text().replace('', '').replace('.',
                        ''));

                    totalWeight += weight;
                    totalPrice += weight * price;
                });

                //Update sumary berdasarkan totalWeight dan totalPrice
                $('.total-weight').text('Jumlah : ' + totalWeight + ' kg');
                $('.total-price').text('Total : ' + totalPrice.toLocaleString('id-ID', {
                    style: 'currency',
                    currency: 'IDR'
                }));


                //panggil function Update saat halaman pertama kali diload
                $('.weight').on('change', function() {
                    var row = $(this).closest('.row');
                    var cartId = row.find('.delete-cart').data('id');
                    var weight = $(this).val();

                    //Update function item di keranjang berdasarkan id item dan weight yang diupdate customer
                    $.ajax({
                        url: '{{ url('/carts/update') }}/' + cartId,
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            weight: weight
                        },

                        //jika request berhasil, maka akan menampilkan toast success
                        success: function(response) {
                            if (response.status === 'success') {
                                row.find('.item-price').text('Rp.' + (response.cart.price/response.cart.weight).toLocaleString(
                                    'id-ID', {
                                        style: 'currency',
                                        currency: 'IDR'
                                    }));
                                toastr.options = {
                                    "positionClass": "toast-top-center",
                                    "progressBar": true,

                                };
                                toastr.success(response.message);
                                updateSummary();
                            } else {
                                //jika request gagal, maka akan menampilkan toast error
                                toastr.options = {
                                    "positionClass": "toast-top-center",
                                    "progressBar": true,
                                };
                                toastr.error(response.message);
                            }
                        },
                        //jika request gagal, maka akan menampilkan toast error
                        error: function() {
                            toastr.options = {
                                "positionClass": "toast-top-center",
                                "progressBar": true,
                            };
                            toastr.error('response.responseJSON.message');
                        }
                    });
                });
            }
    // Hapus item di keranjang (dengan event delegation untuk item dinamis)
    $(document).on('click', '.delete-cart', function() {
        var row = $(this).closest('.cart-item');
        var cartId = $(this).data('id');

        // Hapus item di keranjang berdasarkan id item yang dihapus
        $.ajax({
            url: '{{ url('/carts/delete') }}/' + cartId,
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil',
                    text: response.message,
                    timer: 1500
                });

                row.remove(); // Hapus elemen dari DOM
                updateSummary(); // Update total setelah penghapusan
            },
            error: function(response) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: response.responseJSON ? response.responseJSON.message : 'Terjadi kesalahan.',
                    timer: 1500
                });
            }
        });
    });
});

   </script>
@endpush
