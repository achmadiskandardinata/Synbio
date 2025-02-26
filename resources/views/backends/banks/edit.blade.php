@extends('backends.layouts.app', ['tsitle' => 'Banks'])

@section('content')
<h1>Halaman Edit Bank</h1>
<div class="row match-height">
    <div class="col-md-6 col-12">
        <div class="card border-0 shadow">
            <div class="card-header border-0 bg-primary text-white">
                <h4 class="card-title m-0 font-weight-bold"><i class="bi bi-image"></i> EDIT BANK</h4>
            </div>
            <div class="card-content">
                <div class="card-body">
                    <form class="form form-vertical" method="POST"
                        action="{{ route('admin.banks.update', $bank->id) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <div class="form-body">
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="bank_name">Nama Bank</label>
                                        <input type="text" id="bank_name" name="bank_name"
                                            class="form-control @error('bank_name')
                                        is-invalid @enderror"
                                            value="{{ $bank->bank_name }}" placeholder="Nama Bank">

                                        @error('bank_name')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="Account_number">Nomor Rekening</label>
                                        <input type="text" id="account_number" name="account_number"
                                            class="form-control @error('account_number')
                                        is-invalid @enderror"
                                            value="{{ $bank->account_number }}" placeholder="Nomor Rekening">
                                        @error('account_number')
                                            <div class="invalid-feedback" role="alert">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="Account_name">Nama Pemilik</label>
                                        <input type="text" id="account_name" name="account_name"
                                            class="form-control @error('account_name')
                                        is-invalid @enderror"
                                            value="{{ $bank->account_name }}" placeholder="Nama Pemilik">
                                        @error('account_name')
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
                                        @if ($bank->image && file_exists(public_path('storage/banks/' . $bank->image)))
                                            <img src="{{ asset("storage/banks/{$bank->image}") }}"
                                                alt="{{ $bank->image }}" class="img-fluid" style="max-width: 150px;">
                                        @else
                                            <img src="https://dummyimage.com/1440x600/8f298f/fff.png&text=Bank+Tidak+Ada"
                                                alt="{{ $bank->image }}" class="img-fluid" style="max-width: 150px;">
                                        @endif
                                    </div>
                                </div>

                                <div class="col-12 d-flex justify-content-end">
                                    <button type="submit" class="btn btn-primary me-1 mb-1">Update</button>
                                    <a href="{{ route('admin.banks.index') }}" type="reset"
                                        class="btn btn-light-secondary me-1 mb-1">Back</a>
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

