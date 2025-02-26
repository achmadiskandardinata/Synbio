@extends('backends.layouts.app', ['title' => 'Products'])

@section('content')
<h1>Halaman Edit Produk</h1>
    <div class="row match-height">
        <div class="col-md-6 col-12">
            <div class="card border-0 shadow">
                <div class="card-header border-0 bg-primary text-white">
                    <h4 class="card-title m-0 font-weight-bold"><i class="bi bi-image"></i> EDIT PRODUK</h4>
                </div>
                <div class="card-content">
                    <div class="card-body">
                        <form class="form form-vertical" method="POST" action="{{ route('admin.products.update', $product->id) }}"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-body">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="title">Judul</label>
                                            <input type="text" id="title" name="title"
                                                class="form-control @error('title')
                                            is-invalid @enderror"
                                                value="{{ $product->title }}" placeholder="Judul">

                                            @error('title')
                                                <div class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="description" class="form-label">Deskiprsi</label>
                                            <textarea class="form-control @error('description')
                                            is-invalid @enderror"
                                                id="description" name="description" rows="3">{{ $product->description }}</textarea>
                                            @error('description')
                                                <div class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="image">Foto</label>
                                            <div class="input-group mb-3">
                                                <div class="input-group">
                                                    <input type="file"
                                                        class="form-control @error('image') is-invalid @enderror"
                                                        id="image" name="image">

                                                    @error('image')
                                                        <div class="invalid-feedback" role="alert">
                                                            {{ $message }}
                                                        </div>
                                                    @enderror
                                                </div>
                                            </div>
                                            @if ($product->image && file_exists(public_path('storage/products/' . $product->image)))
                                                <img src="{{ asset("storage/products/{$product->image}") }}" alt="{{ $product->image }}" class="img-fluid" style="max-width: 150px;">
                                            @else
                                                <img src="https://dummyimage.com/1440x600/8f298f/fff.png&text=Product+Tidak+Ada" alt="{{ $product->image }}" class="img-fluid" style="max-width: 150px;">
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="weight">Berat</label>
                                            <input type="text" id="weight" name="weight"
                                                class="form-control @error('weight')
                                            is-invalid @enderror"
                                                value="{{ $product->weight }}" placeholder="Judul">

                                            @error('weight')
                                                <div class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-group">
                                            <label for="price">Harga</label>
                                            <input type="text" id="price" name="price"
                                                class="form-control @error('price')
                                            is-invalid @enderror"
                                                value="{{ $product->price }}" placeholder="Judul">

                                            @error('price')
                                                <div class="invalid-feedback" role="alert">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-12">
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" role="switch" id="status"
                                                name="status" @if ($product->status == 'show') checked @endif>
                                            <label class="form-check-label" for="status">Geser untuk menampilkan</label>
                                        </div>
                                    </div>

                                    <div class="col-12 d-flex justify-content-end">
                                        <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                        <a href="{{route('admin.products.index')}}" type="reset" class="btn btn-light-secondary me-1 mb-1">Back</a>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
