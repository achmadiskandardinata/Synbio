<?php

namespace App\Http\Controllers\Backends;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::latest()->when(request()->q, function($products) {
            // $products = $products->where('title', 'like', '%'. request()->q . '%');
            $products = $products->where(function($query) {
                $query->where('title', 'like', '%'. request()->q . '%')
                    ->orWhere('description', 'like', '%'. request()->q . '%');
            });
        }
        )->orderBy('id', 'desc')->paginate(10);
        return view('backends.products.index', compact('products'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('backends.products.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'weight'=> 'required',
            'price'=> 'required',
        ],
        [
            'title.required'=> 'Silakan masukkan judul',
            'description.required'=> 'Silakan masukkan deskripsi',
            'image.required'=> 'Silakan pilih gambar',
            'image.image'=> 'Silakan pilih gambar yang valid',
            'image.mimes'=> 'Silakan pilih gambar dengan format yang valid',
            'image.max'=> 'Ukuran gambar maksimal adalah 2048KB',
            'weight.required'=> 'Silakan masukkan berat',
            'price.required'=> 'Silakan masukkan harga',
        ]);

        //membuat slug dari title
        $slug = Str::slug($request->title);

        //Cek status show atau hide
        $status = $request->has('status') ? 'show' : 'hide';

        //menyimpan gambar ke dalam folder storage/products
        $image = $request->file('image');
        $image_name = time(). '.' . $image->getClientOriginalExtension();
        $image->move(public_path('storage/products'), $image_name);


        //menyimpan data products ke dalam database
        Product::create([
            'title' => $request->title,
            'slug' => $slug,
            'description' => $request->description,
            'image' => $image_name,
            'weight' => $request->weight,
            'price' => $request->price,
            'status' => $status,
        ]);

        if ($request){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Product Berhasil Ditambahkan');
            return redirect()->route('admin.products.index');
        } else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Product Gagal Ditambahkan');
            return redirect()->route('admin.products.create');
        }


    }

    /**
     * Display the specified resource.
     */
    public function show(product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(product $product)
    {
        return view('backends.products.edit', compact('product'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, product $product)
    {
        $request->validate([
            'title'=> 'required',
            'description'=> 'required',
            'image'=> 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'weight'=> 'required',
            'price'=> 'required',
        ],
        [
            'title.required'=> 'Silakan masukkan judul',
            'description.required'=> 'Silakan masukkan deskripsi',
            'image.image'=> 'Silakan pilih gambar yang valid',
            'image.mimes'=> 'Silakan pilih gambar dengan format yang valid',
            'image.max'=> 'Ukuran gambar maksimal adalah 2MB',
            'weight.required'=> 'Silakan masukkan berat',
            'price.required'=> 'Silakan masukkan harga',
        ]);

        //membuat slug dari title
        $slug = Str::slug($request->title);

        //Cek status show atau hide
        $status = $request->has('status') ? 'show' : 'hide';

        //cek apakah user mengganti gambar
        if ($request->hasFile('image')){
            //hapus gambar lama di folder storage/products
            $old_image = public_path('storage/products/' . $product->image);
            if (file_exists($old_image)){
                unlink($old_image);
            }

            //mnyimpan gambar baru ke dalam folder storage/products
            $image = $request->file('image');
            $image_name = time(). '.' . $image->getClientOriginalExtension();
            $image->move(public_path('storage/products'), $image_name);

            //menyimpan data products ke dalam database
            Product::where('id', $product->id)->update([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'image' => $image_name,
                'weight' => $request->weight,
                'price' => $request->price,
                'status' => $status,
            ]);
        } else {
            //menyimpan data products ke dalam database tanpa mengubah gambar
            Product::where('id', $product->id)->update([
                'title' => $request->title,
                'slug' => $slug,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'status' => $status,
            ]);
        }
        if ($request){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Product Berhasil Diubah');
            return redirect()->route('admin.products.index');
        } else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Product Gagal Diubah');
            return redirect()->route('admin.products.edit');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(product $product)
    {
        if (file_exists(public_path('storage/products/' . $product->image))){
            unlink(public_path('storage/products/' . $product->image));
        }
        Banner::destroy($product->id);
        if ($product->delete()){
            toastr()
            ->positionClass('toast-top-center')
            ->success('Data Product Berhasil Dihapus');
            return redirect()->route('admin.products.index');
        } else{
            toastr()
            ->positionClass('toast-top-center')
            ->error('Data Product Gagal Dihapus');
            return redirect()->route('admin.products.index');
        }
    }
}
