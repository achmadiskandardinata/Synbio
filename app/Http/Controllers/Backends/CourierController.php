<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Courier;
use Illuminate\Support\Str;



class CourierController extends Controller
{
    public function index()
    {
        $couriers = Courier::latest()->when(
            request()->q,
            function ($couriers) {
                // $couriers = $couriers->where('name', 'like', '%'. request()->q . '%');
                $couriers = $couriers->where(function ($query) {
                    $query->where('name', 'like', '%' . request()->q . '%')
                        ->orWhere('service', 'like', '%' . request()->q . '%');
                });
            }
        )->orderBy('id', 'desc')->paginate(10);
        return view('backends.couriers.index', compact('couriers'));
    }


    public function create()
    {
        return view('backends.couriers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'service' => 'required',
            'cost' => 'required',
        ],
        [
            'name.required'=> 'Nama wajib diisi',
            'service.required'=> 'Layanan wajib diisi',
            'cost.required'=> 'Biaya wajib diisi',
        ]);

        courier::create([
            'name' => $request->name,
            'service' => $request->service,
            'cost' => $request->cost,
        ]);
        if ($request){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data berhasil disimpan');
            return redirect()->route('admin.couriers.index');

        }else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data gagal disimpan');
            return redirect()->route('admin.couriers.create');
        }
}

public function edit(Courier $courier)
{
    return view('backends.couriers.edit', compact('courier'));

}

public function update (Request $request, Courier $courier){

    $request->validate([
        'name' => 'required',
        'service' => 'required',
        'cost' => 'required',
    ],
    [
        'name.required'=> 'Nama wajib diisi',
        'service.required'=> 'Layanan wajib diisi',
        'cost.required'=> 'Biaya wajib diisi',
    ]);

    Courier::where('id', $courier->id)->update([
        'name' => $request->name,
        'service' => $request->service,
        'cost' => $request->cost,
    ]);
    if($request){
        toastr()
        ->positionClass('toast-top-center')
        ->success('Data berhasil diubah');
        return redirect()->route('admin.couriers.index');
    }
    else{
        toastr()
        ->positionClass('toast-top-center')
        ->error('Data gagal diubah');
        return redirect()->route('admin.couriers.edit');
    }

}

public function destroy(Courier $courier) {
    Courier::destroy($courier->id);
    if($courier){
        toastr()
        ->positionClass('toast-top-center')
        ->success('Data berhasil dihapus');
        return redirect()->route('admin.couriers.index');
    }
    else{
        toastr()
        ->positionClass('toast-top-center')
        ->error('Data gagal dihapus');
        return redirect()->route('admin.couriers.index');
    }
}
}
