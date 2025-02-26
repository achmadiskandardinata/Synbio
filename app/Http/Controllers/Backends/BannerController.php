<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->when(request()->q, function($banners) {
            // $banners = $banners->where('title', 'like', '%'. request()->q . '%');
            $banners = $banners->where(function($query) {
                $query->where('title', 'like', '%'. request()->q . '%')
                    ->orWhere('subtitle', 'like', '%'. request()->q . '%')
                    ->orWhere('description', 'like', '%'. request()->q . '%');
            });
        }
        )->orderBy('id', 'desc')->paginate(10);
        return view('backends.banners.index', compact('banners'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backends.banners.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'position' => 'required|in:1,2',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'title.required' => 'Judul wajib diisi',
            'subtitle.required' => 'Sub Judul wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'position.required' => 'Posisi wajib diisi',
            'position.in' => 'Posisi tidak valid',
            'image.required' => 'Gambar wajib diisi',
            'image.image' => 'Gambar harus berupa gambar',
            'image.mimes' => 'Gambar harus berupa jpeg, png, jpg, gif, svg',
            'image.max' => 'Gambar harus kurang dari 2MB',
        ]);

        //membuat slug dari title
        $slug = Str::slug($request->title);

        //Cek status show atau hide
        $status = $request->has('status') ? 'show' : 'hide';

        //menyimpan gambar ke dalam folder storage/banners
        $image = $request->file('image');
        $image_name = time(). '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/banners'), $image_name);


        //menyimpan data banners ke dalam database
        Banner::create([
            'title' => $request->title,
            'slug' => $slug,
            'subtitle' => $request->subtitle,
            'description' => $request->description,
            'position' => $request->position,
            'image' => $image_name,
            'status' => $status,
        ]);

        if ($request){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Banner Berhasil Ditambahkan');
            return redirect()->route('admin.banners.index');
        } else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Banner Gagal Ditambahkan');
            return redirect()->route('admin.banners.create');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Banner $banner)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner) // menggunakan variabel ($id) maka perlu diubah parameter dari (Banner $banner) menjadi $id
    {
        //$banner = Banner::find($id);
        return view('backends.banners.edit', compact('banner'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Banner $banner)
    {
        // dd($request->all());
        $request->validate([
            'title' => 'required',
            'subtitle' => 'required',
            'description' => 'required',
            'position' => 'required|in:1,2',
            // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ],
        [
            'title.required' => 'Judul wajib diisi',
            'subtitle.required' => 'Sub Judul wajib diisi',
            'description.required' => 'Deskripsi wajib diisi',
            'position.required' => 'Posisi wajib diisi',
            'position.in' => 'Posisi tidak valid',
            // 'image.required' => 'Gambar wajib diisi',
            // 'image.image' => 'Gambar harus berupa gambar',
            // 'image.mimes' => 'Gambar harus berupa jpeg, png, jpg, gif, svg',
            // 'image.max' => 'Gambar harus kurang dari 2MB',
        ]);

        //membuat slug dari title
        $slug = Str::slug($request->title);

        //Cek status show atau hide
        $status = $request->has('status') ? 'show' : 'hide';

        //cek apakah gambar diubah atau tidak
        if ($request->hasFile('image')) {


            //hapus gambar lama di dalam folder storage/banners
            $oldimage = public_path('storage/banners/' . $banner->image);
            if (file_exists($oldimage)) {
                unlink($oldimage);
                }

            //menyimpan gambar ke dalam folder storage/banners
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/banners'), $image_name);

            //menyimpan data banners ke dalam database
            Banner::where('id', $banner->id)->update([
                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'description' => $request->description,
                'position' => $request->position,
                'image' => $image_name,
                'status' => $status,
            ]);
        } else {
            //menyimpan data banners ke dalam database tanpa mengubah gambar
            Banner::where('id', $banner->id)->update([
                'title' => $request->title,
                'slug' => $slug,
                'subtitle' => $request->subtitle,
                'description' => $request->description,
                'position' => $request->position,
                'status' => $status,
            ]);
        }
        if ($request){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Banner Berhasil Diubah');
            return redirect()->route('admin.banners.index');
        } else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Banner Gagal Diubah');
            return redirect()->route('admin.banners.edit', $banner->id);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Banner $banner)
    {
        if (file_exists(public_path('storage/banners/' . $banner->image))) {
            unlink(public_path('storage/banners/' . $banner->image));
        }
        Banner::destroy($banner->id);
        if ($banner->delete()) {
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Banner Berhasil Dihapus');
            return redirect()->route('admin.banners.index');
        } else {
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Banner Gagal Dihapus');
            return redirect()->route('admin.banners.index');
        }
    }
}
