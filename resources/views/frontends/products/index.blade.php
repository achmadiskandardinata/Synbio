@extends('frontends.layouts.app', ['title' => 'Detail Produk'])

@section('content')
    <section id="produk">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <h4>Semua Produk</h4>
                </div>
                <div class="col-lg-6">
                    <form class="d-flex" role="search" method="get" action="{{ route('products.page') }}">
                        <input class="form-control me-2" type="search" placeholder="cth. bayam, tomat, etc."
                            aria-label="Search" name="q">
                        <button class="btn btn-outline-success" type="submit">Search</button>
                    </form>
                </div>
            </div>

            <div class="row mt-3">
                @foreach ($products as $product)
                    <div class="col-lg-3">
                        <a class="card card-produk" href="{{ route('products.detail', $product->slug) }}">
                            <div class="foto-produk border p-5">
                                @if ($product->image && file_exists(public_path('storage/products/' . $product->image)))
                                    <img src="{{ asset("storage/products/{$product->image}") }}" alt="{{ $product->image }}"
                                        class="card-img-top-fluid">
                                @else
                                    <img src="https://dummyimage.com/1440x600/8f298f/fff.png&text=Poduct+Tidak+Ada"
                                        alt="{{ $product->image }}" class="card-img-top-fluid">
                                @endif
                            </div>
                            <div class="card-body text-center">
                                <h5 class="card-tittle">{{$product->title}}</h5>
                                <p class="card-text">{{moneyFormat($product->price)}}/kg</p>
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
@endsection
