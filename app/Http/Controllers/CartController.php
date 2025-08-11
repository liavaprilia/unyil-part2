<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CartController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Cart::query()
                ->select('carts.*', 'products.product_name', 'products.product_image', 'products.product_price', 'products.product_discount')
                ->join('products', 'products.id_product', '=', 'carts.cart_product_id')
                ->where('cart_user_id', auth()->id());

            $datatables = \Yajra\DataTables\Facades\DataTables::of($query);

            $listSearch = [
                'product_name' => ['products.product_name'],
                'cart_qty' => ['cart_qty'],
            ];

            $datatables = $this->filterDatatable($datatables, $listSearch);

            return $datatables
                ->addIndexColumn()
                ->editColumn('product_name', function ($row) {
                    return '<div class="flex items-center gap-2">
                                <img src="' . Storage::url($row->product_image) . '" alt="' . $row->product_name . '" class="object-cover w-32 h-32 rounded">
                                <span>' . $row->product_name . '</span>
                            </div>';
                })

                // ->addColumn('product_price', fn($row) => "<span class='flex items-center w-full gap-1 text-center'>Rp " . number_format(($row->product_price) ?? 0, 0, ',', '.') . "</span>")
                ->addColumn('product_price', function ($row) {
                    if ($row->product_discount > 0) {
                        $originalPrice = "<span class='text-gray-500 line-through'>Rp " . number_format($row->product_price ?? 0, 0, ',', '.') . "</span>";
                        $discountAmount = ($row->product_price ?? 0) - ($row->product_discount ?? 0);
                        $discountPercent = ($row->product_price > 0) ? round(($row->product_discount / $row->product_price) * 100, 2) : 0;
                        $discountedPrice = "<span class='font-semibold text-black'>Rp " . number_format($discountAmount ?? 0, 0, ',', '.') . "</span>";
                        $discountInfo = "<span class='text-base text-[#fb8763]'>Hemat Rp. " . number_format($row->product_discount, 0, ',', '.') . "</span>";
                        // $discountInfo = "<span class='text-xs text-green-600'>Diskon {$discountPercent}% (-Rp " . number_format($discountAmount, 0, ',', '.') . ")</span>";
                        $discountLabel = "<span class='text-base font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded ml-2'>{$discountPercent}%</span>";
                        return "<span class='flex flex-col items-start w-full gap-1 text-left'>{$originalPrice} <span class='flex items-center gap-2'>{$discountedPrice} {$discountLabel}</span> {$discountInfo}</span>";
                    } else {
                        return "<span class='text-black'>Rp " . number_format($row->product_price ?? 0, 0, ',', '.') . "</span>";
                    }
                })

                // ->addColumn('total_product_price', fn($row) => "<span class='flex items-center w-full gap-1 text-center product-price'>Rp " . number_format(($row->product_price * $row->cart_qty) ?? 0, 0, ',', '.') . "</span>")
                ->addColumn('total_product_price', function ($row) {
                    if ($row->product_discount > 0) {
                        $totalDiscounted = ($row->product_discount ?? 0) * $row->cart_qty;
                        $totalOriginal = ($row->product_price ?? 0) * $row->cart_qty;
                        $discountAmount = $totalOriginal - $totalDiscounted;
                        $discountPercent = ($totalOriginal > 0) ? round($discountAmount / $totalOriginal * 100) : 0;
                        $originalTotal = "<span class='text-gray-500 line-through'>Rp " . number_format($totalOriginal, 0, ',', '.') . "</span>";
                        $discountedTotal = "<span class='font-semibold text-black'>Rp " . number_format($discountAmount, 0, ',', '.') . "</span>";
                        // $discountLabel = "<span class='text-base font-semibold text-[#fb8763] px-1 py-0.5 rounded ml-2'>-{$discountPercent}%</span>";
                        return "<span class='flex flex-col items-start w-full gap-1 text-left'>{$originalTotal} <span class='flex items-center gap-2'>{$discountedTotal}</span></span>";
                        // return "<span class='flex flex-col items-start w-full gap-1 text-left'>{$originalTotal} <span class='flex items-center gap-2'>{$discountedTotal} {$discountLabel}</span></span>";
                    } else {
                        $total = ($row->product_price ?? 0) * $row->cart_qty;
                        return "<span class='text-black'>Rp " . number_format($total, 0, ',', '.') . "</span>";
                    }
                })

                ->addColumn('cart_qty', function ($row) {
                    return '<div class="flex flex-col items-center">
                                <div class="flex items-center">
                                <div class="flex items-center border border-black rounded max-w-fit">
                                    <button type="button" data-action="remove" data-id="' . $row->product->id_product . '"
                                        class="btn-update-cart flex items-center justify-center w-8 h-8 py-[1.25rem] text-xl font-bold decrement-btn"
                                        data-row="' . $row->id_cart . '">-</button>
                                    <input readonly id="quantity-' . $row->id_cart . '" name="quantity[]" type="number" min="1" value="' . $row->cart_qty . '"
                                        class="w-16 text-center border-none focus:outline-none focus:ring-2 focus:ring-black quantity-input"
                                        style="appearance: textfield; -moz-appearance: textfield;" data-row="' . $row->id_cart . '" />
                                    <button type="button" data-action="add" data-id="' . $row->product->id_product . '"
                                        class="btn-update-cart flex items-center justify-center w-8 h-8 py-[1.25rem] text-xl font-bold increment-btn"
                                        data-row="' . $row->id_cart . '">+</button>
                                </div>
                                </div>
                                <div class="text-sm text-gray-500 product-stock">Tersedia: ' . ($row->product->product_stock ?? 0) . '</div>
                            </div>';
                })
                ->addColumn('action', function ($row) {
                    return '<div class="flex flex-col items-center gap-2">
                                <button data-id="' . $row->id_cart . '" class="flex items-center justify-center w-24 px-3 py-1 text-black transition bg-white border border-black rounded btn-delete-cart hover:bg-black hover:text-white"
                                    title="Delete">
                                    <span class="mr-2 ti ti-trash"></span>
                                    Delete
                                </button>
                            </div>';
                })
                ->rawColumns(['product_name', 'product_price', 'total_product_price', 'cart_qty', 'action'])
                ->make(true);
        }
        // $data['total_price'] = number_format(
        //     Cart::where('cart_user_id', auth()->id())
        //         ->join('products', 'products.id_product', '=', 'carts.cart_product_id')
        //         ->sum(DB::raw('carts.cart_qty * products.product_price')), 0, ',', '.'
        // );
        // $data['total_disount'] = number_format(
        //     Cart::where('cart_user_id', auth()->id())
        //         ->join('products', 'products.id_product', '=', 'carts.cart_product_id')
        //         ->sum(DB::raw('carts.cart_qty * products.product_discount')), 0, ',', '.'
        // );
        $data['total_price'] = number_format(
            Cart::where('cart_user_id', auth()->id())
                ->join('products', 'products.id_product', '=', 'carts.cart_product_id')
                ->sum(DB::raw('carts.cart_qty * (products.product_price - products.product_discount)')), 0, ',', '.'
        );
        // dd($data);
        return view('cart', $data);
    }

    public function updateCart(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id_product' => 'required|exists:products,id_product',
                'quantity' => 'required|integer|min:1',
                'action' => 'required|in:add,remove',
            ]);

            $cart = Cart::where('cart_user_id', auth()->id())
                ->where('cart_product_id', $request->id_product)
                ->first();

            if ($request->action === 'add') {
                if ($cart) {
                    $product = $cart->product;
                    if ($product->product_stock == 0) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Stok habis. Silakan coba lagi nanti ketika produk tersedia kembali.',
                        ], 422);
                    }

                    $cart->cart_qty += $request->quantity;
                    $cart->save();

                    if (($product->product_stock ?? 0) < $cart->cart_qty) {
                        return response()->json([
                            'success' => false,
                            'message' => "Stok hanya tersisa {$product->product_stock} item. Silakan ubah jumlah pesanan Anda."
                        ], 422);
                    }

                    $cartQty = $cart->cart_qty;
                } else {
                    $product = Product::find($request->id_product);
                    $cart = Cart::create([
                        'cart_user_id' => auth()->id(),
                        'cart_product_id' => $request->id_product,
                        'cart_qty' => $request->quantity,
                    ]);

                    if ($product) {

                        if ($product->product_stock == 0) {
                            return response()->json([
                                'success' => false,
                                'message' => 'Stok habis. Silakan coba lagi nanti ketika produk tersedia kembali.',
                            ], 422);
                        }

                        if ($product->product_stock < $cart->cart_qty) {
                            return response()->json([
                                'success' => false,
                                'message' => "Stok hanya tersisa {$product->product_stock} item. Silakan ubah jumlah pesanan Anda."
                            ], 422);
                        }

                        $cartQty = $cart->cart_qty;
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Barang tidak ditemukan.',
                        ], 404);
                    }
                }
            } elseif ($request->action === 'remove') {
                if ($cart) {
                    $product = $cart->product;
                    if ($cart->cart_qty > $request->quantity) {
                        $cart->cart_qty -= $request->quantity;
                        $cart->save();

                        $cartQty = $cart->cart_qty;
                    } else {
                        $cartQty = $cart->cart_qty;
                        $cart->delete();

                        // Kirim cart_qty = 0 secara eksplisit
                        $cartQty = 0;
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Barang tidak ditemukan di keranjang.',
                    ], 404);
                }
            }

            DB::commit();
            $action = $request->action === 'add' ? 'menambahkan' : 'mengurangi';
            $currentCart = Cart::where('cart_user_id', auth()->id());
            return response()->json([
                'success' => true,
                'message' => 'Berhasil ' . $action . ' barang ke keranjang.',
                'data' => [
                    'total_items' => $currentCart->count(),
                    // 'cart_qty' => $cartQty ?? (int) $request->quantity,
                    'cart_qty' => $currentCart->where('cart_product_id', $request->id_product)->sum('cart_qty'),
                    'total_price' => number_format(($cartQty ?? (int) $request->quantity) * ($product->product_price ?? 0), 0, ',', '.'),
                    'cart_total_price' => number_format(
                        Cart::where('cart_user_id', auth()->id())
                            ->join('products', 'products.id_product', '=', 'carts.cart_product_id')
                            ->sum(DB::raw('carts.cart_qty * products.product_price')), 0, ',', '.'
                    ),
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            $action = $request->action === 'add' ? 'menambahkan' : 'mengurangi';

            return response()->json([
                'success' => false,
                'message' => 'Gagal ' . $action . ' barang: ' . $e->getMessage(),
            ], 422);
        }
    }

    public function deleteCart(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'id_cart' => 'required|exists:carts,id_cart',
            ]);

            $cart = Cart::where('cart_user_id', auth()->id())
                ->where('id_cart', $request->id_cart)
                ->first();

            if ($cart) {
                $cart->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Barang berhasil dihapus dari keranjang.',
                    'data' => [
                        'total_items' => Cart::where('cart_user_id', auth()->id())->count(),
                    ]
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Barang tidak ditemukan di keranjang.',
                ], 404);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus barang: ' . $e->getMessage(),
            ], 422);
        }
    }
}
