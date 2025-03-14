@extends('frontends.layouts.app', ['title' => '404 Not Found'])

@section('content')
    <section>
        <div class="container text-center" style="margin-top: 50px">
            <div class="row">
                <div class="col-md-12">
                    <h1 style="font-size: 100px; color: #e74c3c;">404</h1>
                    <h1> Oops! Halaman tidak ditemukan</h2>
                        <p>Maaf, halaman yang Anda cari tidak dapat ditemukan.</p>
                        @if (Auth::guard('admin')->check())
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-primary">Kembali ke Dashboard</a>
                        @elseif (Auth::guard('web')->check())
                            <a href="{{ route('home') }}" class="btn btn-primary" style="margin-top: 20px;">Kembali Ke Beranda</a>
                        @else
                            <a href="{{ route('home.page') }}" class="btn btn-primary" style="margin-top: 20px;">Kembali Ke Beranda</a>
                        @endif
                </div>
            </div>
        </div>
    </section>
@endsection
