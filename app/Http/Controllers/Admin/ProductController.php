<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = DB::table('products')->select([
                'id_product',
                'product_name',
                'product_price',
                'product_discount',
                'product_desc',
                'product_weight',
                'product_stock',
                'product_image',
                'created_at'
            ]);

            $datatables = DataTables::of($query);

            // Define searchable columns
            $listSearch = [
                'product_name' => ['product_name'],
                'product_price' => ['product_price'],
                'product_discount' => ['product_discount'],
                'product_desc' => ['product_desc'],
                'product_stock' => ['product_stock'],
            ];

            $datatables = $this->filterDatatable($datatables, $listSearch);

            return $datatables
                ->addIndexColumn()
                ->addColumn('product_image', function ($row) {
                    return '<img src="' . asset('storage/' . $row->product_image) . '" alt="Product" class="object-cover w-24 h-32 rounded">';
                })
                ->addColumn('formatted_price', function ($row) {
                    if ($row->product_discount > 0) {
                        $originalPrice = number_format($row->product_price, 0, ',', '.');
                        // $discountedPrice = number_format($row->product_price - ($row->product_price * ($row->product_discount / 100)), 0, ',', '.');
                        $discountedPrice = number_format($row->product_price - $row->product_discount, 0, ',', '.');
                        $discountPercentage = round(($row->product_discount / $row->product_price) * 100, 2);

                        return '
                            <div class="flex items-center space-x-2">
                                <span class="px-2 py-1 text-xs font-bold text-white bg-green-600 rounded">' . $discountPercentage . '%</span>
                                <div class="flex flex-col leading-tight">
                                    <span class="text-sm text-gray-500 line-through">Rp ' . $originalPrice . '</span>
                                    <span class="text-base font-semibold text-red-600">Rp ' . $discountedPrice . '</span>
                                </div>
                            </div>
                        ';
                    } else {
                        return '<span class="font-semibold text-gray-700">Rp ' . number_format($row->product_price, 0, ',', '.') . '</span>';
                    }
                })
                ->addColumn('formatted_discount', function ($row) {
                    // return $row->product_discount ? $row->product_discount . '%' : '-';
                    return 'Rp ' . number_format($row->product_discount, 0, ',', '.');
                })
                ->addColumn('action', function ($row) {
                    return '
                        <div class="flex flex-col items-center gap-2">
                            <a href="' . route('admin.products.show', $row->id_product) . '" class="flex items-center justify-center w-24 px-3 py-1 text-black transition border border-black rounded hover:bg-gray-200" title="Preview">
                                <span class="mr-1 ti ti-brand-google-podcasts"></span>
                                Preview
                            </a>
                            <a href="' . route('admin.products.edit', $row->id_product) . '" class="flex items-center justify-center w-24 px-3 py-1 text-white transition bg-black border border-black rounded hover:bg-gray-800" title="Edit">
                                <span class="mr-1 ti ti-edit"></span>
                                Edit
                            </a>
                            <button onclick="deleteProduct(this)" data-id="' . $row->id_product . '" class="flex items-center justify-center w-24 px-3 py-1 text-black transition bg-white border border-black rounded hover:bg-black hover:text-white" title="Delete">
                                <span class="mr-1 ti ti-trash"></span>
                                Delete
                            </button>
                        </div>
                    ';
                })
                ->rawColumns(['product_image', 'action', 'formatted_price', 'formatted_discount'])
                ->make(true);
        }
        return view('admin.products.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data = [
            'routeForm' => route('admin.products.store'),
            'buttonText' => 'Simpan Produk',
            'title' => 'Tambah Produk',
        ];

        return view('admin.products.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->merge([
                'product_price' => str_replace(['Rp', '.', ' '], '', $request->input('product_price')),
                'product_discount' => str_replace(['Rp', '.', ' '], '', $request->input('product_discount', 0) ?? 0),
            ]);
            $request->validate([
                'product_name' => 'required|string|max:255|unique:products,product_name',
                'product_price' => 'required|numeric|min:1',
                'product_discount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value > $request->input('product_price')) {
                            $fail('Diskon tidak boleh lebih besar dari harga produk.');
                        }
                    },
                ],
                'product_weight' => 'required|numeric|min:1',
                'product_stock' => 'nullable|integer|min:0',
                'product_desc' => 'required|string|max:1000',
                'product_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'product_name.required' => 'Nama produk harus diisi.',
                'product_name.unique' => 'Nama produk sudah ada.',
                'product_price.required' => 'Harga produk harus diisi.',
                'product_price.numeric' => 'Harga produk harus berupa angka.',
                'product_discount.numeric' => 'Diskon produk harus berupa angka.',
                'product_discount.min' => 'Diskon produk tidak boleh kurang dari 0.',
                'product_weight.required' => 'Berat produk harus diisi.',
                'product_weight.numeric' => 'Berat produk harus berupa angka.',
                'product_stock.integer' => 'Stok produk harus berupa angka.',
                'product_desc.required' => 'Deskripsi produk harus diisi.',
                'product_image.required' => 'Gambar produk harus diunggah.',
                'product_image.image' => 'File yang diunggah harus berupa gambar.',
                'product_image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
                'product_image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            // Handle file upload
            if ($request->hasFile('product_image')) {
                $imagePath = $request->file('product_image')->store('products', 'public');
            } else {
                throw new \Exception('Image is required');
            }

            $product = Product::create([
                'product_updated_by' => auth()->id(),
                'product_name' => $request->input('product_name'),
                'product_price' => $request->input('product_price'),
                'product_discount' => $request->input('product_discount', 0) ?? 0,
                'product_weight' => $request->input('product_weight'),
                'product_stock' => $request->input('product_stock', 0),
                'product_desc' => $request->input('product_desc'),
                'product_image' => $imagePath,
            ]);

            DB::commit();

            // return json success message redirect
            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dibuat.',
                'data' => [
                    'redirect_url' => route('admin.products.index'),
                ]
            ], 200);
            // catch validation errors
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi formulir dengan benar.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat produk.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $product = Product::where('id_product', $id)->firstOrFail();
            return view('admin.products.detail', compact('product'));
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->withErrors(['error' => 'Product not found']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $product = Product::where('id_product', $id)->first();
            $data = [
                'routeForm' => route('admin.products.update', $product->id_product),
                'buttonText' => 'Perbarui Produk',
                'title' => 'Edit Produk',
                'product' => $product,
            ];

            return view('admin.products.create', $data);
        } catch (\Exception $e) {
            return redirect()->route('admin.products.index')->withErrors(['error' => 'Product not found']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $request->merge([
                'product_price' => str_replace(['Rp', '.', ' '], '', $request->input('product_price')),
                'product_discount' => str_replace(['Rp', '.', ' '], '', $request->input('product_discount', 0) ?? 0),
            ]);
            $request->validate([
                'product_name' => 'required|string|max:255|unique:products,product_name,' . $id . ',id_product',
                'product_price' => 'required|numeric|min:1',
                'product_discount' => [
                    'nullable',
                    'numeric',
                    'min:0',
                    function ($attribute, $value, $fail) use ($request) {
                        if ($value > $request->input('product_price')) {
                            $fail('Diskon tidak boleh lebih besar dari harga produk.');
                        }
                    },
                ],
                'product_weight' => 'required|numeric|min:1',
                'product_stock' => 'nullable|integer|min:0',
                'product_desc' => 'required|string|max:1000',
                'product_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ], [
                'product_name.required' => 'Nama produk harus diisi.',
                'product_name.unique' => 'Nama produk sudah ada.',
                'product_price.required' => 'Harga produk harus diisi.',
                'product_price.numeric' => 'Harga produk harus berupa angka.',
                'product_discount.numeric' => 'Diskon produk harus berupa angka.',
                'product_discount.min' => 'Diskon produk tidak boleh kurang dari 0.',
                'product_discount.max' => 'Diskon produk tidak boleh lebih dari harga produk.',
                'product_weight.required' => 'Berat produk harus diisi.',
                'product_weight.numeric' => 'Berat produk harus berupa angka.',
                'product_stock.integer' => 'Stok produk harus berupa angka.',
                'product_desc.required' => 'Deskripsi produk harus diisi.',
                'product_image.image' => 'File yang diunggah harus berupa gambar.',
                'product_image.mimes' => 'Gambar harus berformat jpeg, png, jpg, atau gif.',
                'product_image.max' => 'Ukuran gambar tidak boleh lebih dari 2MB.',
            ]);

            $product = Product::where('id_product', $id)->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Handle file upload
            if ($request->hasFile('product_image')) {
                // Delete old image if exists
                // if ($product->product_image && file_exists(storage_path('app/public/' . $product->product_image))) {
                //     unlink(storage_path('app/public/' . $product->product_image));
                // }
                $imagePath = $request->file('product_image')->store('products', 'public');
            } else {
                $imagePath = $product->product_image; // Keep the old image if no new one is uploaded
            }

            $product->update([
                'product_updated_by' => auth()->user()->id,
                'product_name' => $request->input('product_name'),
                'product_price' => $request->input('product_price'),
                'product_discount' => $request->input('product_discount', 0) ?? 0,
                'product_weight' => $request->input('product_weight'),
                'product_stock' => $request->input('product_stock', 0),
                'product_desc' => $request->input('product_desc'),
                'product_image' => $imagePath,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil diperbarui.',
                'data' => [
                    'redirect_url' => route('admin.products.index'),
                ]
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Silakan isi formulir dengan benar.',
                'errors' => $e->validator->errors(),
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal memperbarui produk.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $product = Product::where('id_product', $id)->first();

            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Delete image file if exists
            // if ($product->product_image && file_exists(storage_path('app/public/' . $product->product_image))) {
            //     unlink(storage_path('app/public/' . $product->product_image));
            // }

            $product->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Produk berhasil dihapus.',
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Produk gagal dihapus.',
            ], 500);
        }
    }
}
