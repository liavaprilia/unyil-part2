<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cartItems = Cart::with('product')->where('cart_user_id', auth()->id())->get();
        $totalWeight = $cartItems->sum(function ($item) {
            return $item->product->product_weight * $item->cart_qty;
        });
        $subTotal = $cartItems->sum(function ($item) {
            return $item->product->product_price * $item->cart_qty;
        });
        $totalDiscount = $cartItems->sum(function ($item) {
            return $item->product->product_discount * $item->cart_qty;
        });

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')->with('error', 'Keranjang belanja Anda kosong.');
        }
        $data = [
            'cartItems' => $cartItems,
            'totalWeight' => $totalWeight,
            'subTotal' => $subTotal,
            'totalDiscount' => $totalDiscount,
        ];
        return view('checkout', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $rules = [
                'recipient_name' => 'required|string|max:255',
                // 'phone' => 'required|string|max:20',
                // no indonesia awalan 08 +62
                'phone' => [
                    'required',
                    'string',
                    'min:12',
                    'max:20',
                    'regex:/^(?:\+62|62|08)[0-9]/',
                ],

                'address' => 'required|string|max:500',
                'postal_code' => 'required|string|max:10',
                'province' => 'required|string|max:100',
                'from_city_id' => 'required|integer',
                'city' => 'required|string|max:100',
                'to_city_id' => 'required|string|max:100',
                'cart_items' => 'required|array',
                'cart_items.*.product_name' => 'required|string|max:255',
                'cart_items.*.product_desc' => 'required|string|max:500',
                'cart_items.*.product_image' => 'required|string|max:255',
                'cart_items.*.product_id' => 'required|uuid',
                'cart_items.*.cart_qty' => 'required|integer|min:1',
                'cart_items.*.product_weight' => 'required|numeric|min:0',
                'cart_items.*.total_product_weight' => 'required|numeric|min:0',
                'cart_items.*.product_price' => 'required|numeric|min:0',
                'cart_items.*.total_product_price' => 'required|numeric|min:0',
                'cart_items.*.discount' => 'required|numeric|min:0',
                'sub_total' => 'required|numeric|min:0',
                'district' => 'required|string|max:100',
                'sub_district' => 'required|string|max:100',
                'shipping_method' => 'required|string|max:50|not_in:no_shipping',
                'payment_method' => 'required|string|max:50',
                'order_note' => 'nullable|string|max:500',
                'payment_proof' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // max 2MB
                'total_weight' => 'required|numeric|min:0',
                'total_price' => 'required|numeric|min:0',
                'shipping_price' => 'required|numeric|min:0',
                'total_discount' => 'required|numeric|min:0',
            ];

            $messages = [
                'recipient_name.required' => 'Nama penerima harus diisi.',
                'phone.required' => 'Nomor telepon harus diisi.',
                'phone.regex' => 'Nomor telepon harus diawali dengan +62 atau 08.',
                'address.required' => 'Alamat harus diisi.',
                'postal_code.required' => 'Kode pos harus diisi.',
                'province.required' => 'Provinsi harus diisi.',
                'city.required' => 'Kota harus diisi.',
                'district.required' => 'Kecamatan harus diisi.',
                'sub_district.required' => 'Kelurahan harus diisi.',
                'cart_items.required' => 'Keranjang belanja tidak boleh kosong.',
                'cart_items.*.product_name.required' => 'Nama produk harus diisi.',
                'cart_items.*.product_desc.required' => 'Deskripsi produk harus diisi.',
                'cart_items.*.product_image.required' => 'Gambar produk harus diisi.',
                'cart_items.*.product_id.required' => 'ID produk harus diisi.',
                'cart_items.*.cart_qty.required' => 'Jumlah produk harus diisi.',
                'cart_items.*.product_weight.required' => 'Berat produk harus diisi.',
                'cart_items.*.total_product_weight.required' => 'Total berat produk harus diisi.',
                'cart_items.*.product_price.required' => 'Harga produk harus diisi.',
                'cart_items.*.total_product_price.required' => 'Total harga produk harus diisi.',
                'sub_total.required' => 'Subtotal harus diisi.',
                'shipping_method.required' => 'Metode pengiriman harus diisi.',
                'payment_method.required' => 'Metode pembayaran harus diisi.',
                'order_note.max' => 'Catatan pesanan tidak boleh lebih dari 500 karakter.',
                'payment_proof.required' => 'Bukti pembayaran harus diunggah.',
                'payment_proof.image' => 'Bukti pembayaran harus berupa gambar.',
                'payment_proof.mimes' => 'Bukti pembayaran harus berupa file dengan tipe: jpeg, png, jpg, gif, atau svg.',
                'payment_proof.max' => 'Bukti pembayaran tidak boleh lebih dari 2MB.',
                'total_weight.required' => 'Total berat harus diisi.',
                'total_price.required' => 'Total harga harus diisi.',
                'shipping_price.required' => 'Harga pengiriman harus diisi.',
                'total_weight.numeric' => 'Total berat harus berupa angka.',
                'total_price.numeric' => 'Total harga harus berupa angka.',
                'shipping_price.numeric' => 'Harga pengiriman harus berupa angka.',
                'shipping_method.not_in' => 'Metode pengiriman tidak boleh kosong.',
            ];

            $validatedData = $request->validate($rules, $messages);

            $cart = Cart::where('cart_user_id', auth()->id());

            if (!$cart->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Keranjang belanja Anda kosong.',
                ], 400);
            }

            // $sellerId = $request->seller_id;
            $transaction = auth()->user()->transactions()->create([
                'transaction_user_id' => auth()->id(),
                'transaction_updated_by' => null,
                'transaction_status' => 'processed',
                'transaction_note' => $request->order_note,
                'transaction_pay_method' => $request->payment_method,
                'transaction_pay_proof' => $request->payment_proof ? $request->file('payment_proof')->store('payments', 'public') : null,
                'tshipping_proof' => null, // Set later after shipping
                'tshipping_tracking_number' => null, // Set later after shipping
                'tshipping_method' => $request->shipping_method,
                'tshipping_receipt_name' => $request->recipient_name,
                'tshipping_phone' => $request->phone,
                'tshipping_country' => 'Indonesia', // Assuming country is Indonesia
                'tshipping_address' => $request->address,
                'tshipping_zip_code' => $request->postal_code,
                'tshipping_provience' => $request->province,
                'tshipping_city' => $request->city,
                'tshipping_district' => $request->district,
                'tshipping_subdistrict' => $request->sub_district,
                'total_weight' => $validatedData['total_weight'],
                'tshipping_price' => $validatedData['shipping_price'],
                'subtotal_price' => $validatedData['sub_total'],
                'total_discount' => $validatedData['total_discount'],
                'total_price' => $validatedData['total_price'],
                'total_quantity' => $cart->sum('cart_qty'),
            ]);

            // Create transaction details
            foreach ($validatedData['cart_items'] as $item) {
                $totalPrice = $item['cart_qty'] * $item['product_price'];
                $totalDiscount = $item['cart_qty'] * $item['discount'];
                $transaction->details()->create([
                    'tdproduct_name' => $item['product_name'],
                    'tdproduct_desc' => $item['product_desc'],
                    'tdproduct_price' => $item['product_price'],
                    'tdproduct_qty' => $item['cart_qty'],
                    'tdproduct_img' => $item['product_image'],
                    'tdproduct_weight' => $item['product_weight'],
                    'tdproduct_total_weight' => $item['total_product_weight'],
                    'tdproduct_discount' => $item['discount'] ?? 0,
                    'tdproduct_total_discount' => $totalDiscount ?? 0,
                    'tdproduct_total_price' => $totalPrice,
                ]);

                $product = Product::find($item['product_id']);
                if ($product) {
                    $product->decrement('product_stock', $item['cart_qty']);
                    $product->save();
                    $product = Product::find($item['product_id']);
                    if ($product->product_stock < 1) {
                        $product->product_stock = 0;
                        $product->save();

                        // atasi product yang sudah ada di cart orang lain
                        Cart::where('cart_product_id', $item['product_id'])
                            ->where('cart_user_id', '!=', auth()->id())
                            ->delete(); // Hapus dari cart semua user yang memiliki produk ini
                    } else {
                        // ambil barang pada cart orang lain lebih dari stock
                        $carts = Cart::where('cart_product_id', $item['product_id'])
                            ->where('cart_user_id', '!=', auth()->id())
                            ->where('cart_qty', '>', $product->product_stock)
                            ->get();
                        // paksa update sesuai stock yang ada
                        foreach ($carts as $cart) {
                            $cart->cart_qty = $product->product_stock;
                            $cart->save();
                        }
                    }
                }
            }

            $cart->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil dibuat.',
                'data' => [
                    'transaction_id' => $transaction->id_transaction,
                    'transaction_details' => $transaction->details->pluck('id_transaction_detail'),
                    'total_price' => $transaction->total_price,
                    'shipping_price' => $transaction->tshipping_price,
                    'subtotal_price' => $transaction->subtotal_price,
                    'redirect_url' => route('home'),
                ],
            ]);
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
                'message' => 'Gagal Membuat Pesanan.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
