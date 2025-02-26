<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bank;
use illuminate\Support\Str;


class BankController extends Controller
{
    public function index()
    {
        $banks = bank::latest()->when(
            request()->q,
            function ($banks) {
                //     $banks = $banks->where('bank_name', 'like', '%' . request()->q . '%');
                // })->paginate(10);
                $banks = $banks->where(function ($query) {
                    $query->where('bank_name', 'like', '%' . request()->q . '%')
                        ->orWhere('account_name', 'like', '%' . request()->q . '%')
                        ->orWhere('account_number', 'like', '%' . request()->q . '%');
                });
            }
        )->orderby('id', 'desc')->paginate(10);
        return view('backends.banks.index', compact('banks'));
    }


    public function create()
    {
        return view('backends.banks.create');
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'bank_name' => 'required',
                'account_name' => 'required',
                'account_number' => 'required',
                'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'bank_name.required' => 'Nama Bank wajib diisi',
                'account_name.required' => 'Nama Pemilik wajib diisi',
                'account_number.required' => 'Nomor Rekening wajib diisi',
                'image.required' => 'Gambar wajib diisi',
                'image.image' => 'Gambar harus berupa gambar',
                'image.mimes' => 'Gambar harus berupa jpeg, png, jpg, gif, svg',
                'image.max' => 'Gambar harus kurang dari 2MB',
            ]
        );

        $image = $request->file('image');
        $image_name = time() . '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/banks'), $image_name);

        Bank::create([
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            'account_number' => $request->account_number,
            'image' => $image_name,
        ]);
        if ($request) {
            toastr()
                ->positionClass('toast-top-center')
                ->success('Data Bank Berhasil Ditambahkan');
            return redirect()->route('admin.banks.index');
        } else {
            toastr()
                ->positionClass('toast-top-center')
                ->error('Data Bank Gagal Ditambahkan');
        }
    }

    public function edit(bank $bank)
    {
        return view('backends.banks.edit', compact('bank'));
    }

    public function update(Request $request, bank $bank)
    {
        // dd($Request->all());
        $request->validate(
            [
                'bank_name' => 'required',
                'account_number' => 'required',
                'account_name' => 'required',
                // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ],
            [
                'bank_name.required' => 'Nama Bank wajib diisi',
                'account_name.required' => 'Nama Pemilik wajib diisi',
                'account_number.required' => 'Nomor Rekening wajib diisi',
                // 'image.required' => 'Gambar wajib diisi',
                // 'image.image' => 'Gambar harus berupa gambar',
                // 'image.mimes' => 'Gambar harus berupa jpeg, png, jpg, gif, svg',
                // 'image.max' => 'Gambar harus kurang dari 2MB',
            ]
        );


        //cek apakah gambar diubah atau tidak
        if ($request->hasFile('image')) {


            //hapus gambar lama di dalam folder storage/banks
            $oldimage = public_path('storage/banks/' . $bank->image);
            if (file_exists($oldimage)) {
                unlink($oldimage);
            }

            //menyimpan gambar ke dalam folder storage/banks
            $image = $request->file('image');
            $image_name = time() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/banks'), $image_name);

            //menyimpan data banks ke dalam database
            Bank::where('id', $bank->id)->update([
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
                'image' => $image_name,
            ]);
        } else {
            //menyimpan data banks ke dalam database tanpa mengubah gambar
            Bank::where('id', $bank->id)->update([
                'bank_name' => $request->bank_name,
                'account_name' => $request->account_name,
                'account_number' => $request->account_number,
            ]);
        }
        if ($request) {
            toastr()
                ->positionClass('toast-top-center')
                ->success('Data Bank Berhasil Diubah');
            return redirect()->route('admin.banks.index');
        } else {
            toastr()
                ->positionClass('toast-top-center')
                ->error('Data Bank Gagal Diubah');
            return redirect()->route('admin.banks.index');
        }
    }

    public function destroy(Bank $bank)
    {
        if (file_exists(public_path('storage/banks/' . $bank->image))) {
            unlink(public_path('storage/banks/' . $bank->image));
        }
        Bank::destroy($bank->id);
        if ($bank->delete()) {
            toastr()
                ->positionClass('toast-top-center')
                ->success('Data Bank Berhasil Dihapus');
            return redirect()->route('admin.banks.index');
        } else {
            toastr()
                ->positionClass('toast-top-center')
                ->error('Data Bank Gagal Dihapus');
            return redirect()->route('admin.banks.index');
        }
    }
}
