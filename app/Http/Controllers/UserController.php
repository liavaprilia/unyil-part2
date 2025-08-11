<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $perPage = 12;
            $page = $request->get('page', 1);

            $products = \App\Models\Product::orderBy('created_at', 'desc')
                ->where('product_stock', '>', 0)
                ->skip(($page - 1) * $perPage)
                ->take($perPage + 1)
                ->get();

            $hasMore = $products->count() > $perPage;
            $products = $products->take($perPage);

            $html = view('components.product_cards', compact('products'))->render();

            return response()->json([
                'html' => $html,
                'hasMore' => $hasMore,
            ]);
        }

        return view('home');
    }

    public function orders(Request $request)
    {
        if ($request->ajax()) {
            // using datatable ajax
            $query = Transaction::query()
                ->with(['user', 'details'])
                ->where('transaction_user_id', auth()->id());

            $datatables = \Yajra\DataTables\Facades\DataTables::of($query);

            $listSearch = [
                'id_transaction' => ['id_transaction'],
                'tshipping_receipt_name' => ['tshipping_receipt_name', 'transaction_note'],
                'created_at' => ['created_at'],
                'details_count' => ['total_quantity'],
                'total_price' => ['total_price'],
                'status' => ['transaction_status'],
            ];

            $datatables = $this->filterDatatable($datatables, $listSearch);

            return $datatables
                ->addIndexColumn()
                ->addColumn('id_transaction', function (Transaction $transaction) {
                    return "<div class='px-4 py-2'>"
                        . $transaction->id_transaction . "</div>";
                })
                ->addColumn('tshipping_receipt_name', function (Transaction $transaction) {
                    return "<div class='px-4 py-2'>"
                        . $transaction->tshipping_receipt_name ?? '-' . "</div>";
                })
                ->addColumn('created_at_display', function (Transaction $transaction) {
                    return "<div class='px-4 py-2 text-center'>"
                        . $transaction->created_at->translatedFormat('d F Y') . "</div>";
                })
                ->addColumn('details_count', function (Transaction $transaction) {
                    return "<div class='px-4 py-2 text-center'>"
                        . $transaction->details->sum('tdproduct_qty') . "</div>";
                })
                ->addColumn('total_price', function (Transaction $transaction) {
                    if ($transaction->total_discount > 0) {
                        $originalPrice = "<span class='text-gray-500 line-through'>Rp " . number_format(($transaction->total_price) ?? 0, 0, ',', '.') . "</span>";
                        $discountAmount = ($transaction->total_price + $transaction->total_discount) - ($transaction->total_price ?? 0);
                        $discountPercent = (($transaction->total_price + $transaction->total_discount) > 0) ? round($discountAmount / ($transaction->total_price + $transaction->total_discount) * 100) : 0;
                        $discountedPrice = "<span class='font-semibold text-black'>Rp " . number_format($transaction->total_price - $transaction->total_discount ?? 0, 0, ',', '.') . "</span>";
                        $discountInfo = "<span class='text-base text-[#fb8763]'>Hemat Rp. " . number_format($discountAmount, 0, ',', '.') . "</span>";
                        $discountLabel = "<span class='text-base font-semibold text-[#fb8763] bg-red-100 px-1 py-0.5 rounded ml-2'>{$discountPercent}%</span>";
                        return "<span class='flex flex-col items-start w-full gap-1 text-left'>{$originalPrice} <span class='flex items-center gap-2'>{$discountedPrice} {$discountLabel}</span> {$discountInfo}</span>";
                    } else {
                        return "<div class='px-4 py-2 font-semibold text-black'>"
                            . 'Rp ' . number_format($transaction->total_price ?? 0, 0, ',', '.') . "</div>";
                    }
                })
                ->addColumn('status', function (Transaction $transaction) {
                    $color = match ($transaction->transaction_status) {
                        'processed' => 'bg-yellow-100 text-yellow-700',
                        'shipped' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                    };
                    $text = match ($transaction->transaction_status) {
                        'processed' => 'PROSES',
                        'shipped' => 'DIKIRIM',
                        'completed' => 'SELESAI',
                        'cancelled' => 'DIBATALKAN',
                        default => 'UNKNOWN',
                    };
                    return "<div class='flex items-center justify-center w-full'>"
                        . "<span class='inline-block px-2 py-1 text-xs font-semibold text-center $color rounded'>"
                        . ucfirst($text) . "</span></div>";
                })
                ->addColumn('action', function (Transaction $transaction) {
                    return '<div class="flex flex-col items-center gap-2 px-4 py-2 text-center">
                    <a href="' . route('order-detail', $transaction->id_transaction) . '" class="flex items-center justify-center w-24 px-3 py-1 text-black transition border border-black rounded hover:bg-gray-200"
                        title="Preview">
                        <span class="mr-1 ti ti-brand-google-podcasts"></span>
                        Lihat
                    </a>
                </div>';
                })
                ->rawColumns(['id_transaction', 'tshipping_receipt_name', 'created_at_display', 'details_count', 'total_price', 'status', 'action'])
                ->make(true);
        }

        return view('orders');
    }

    public function orderDetail(string $id)
    {
        $data['transaction'] = Transaction::with(['user', 'details'])
            ->where('id_transaction', $id)
            ->where('transaction_user_id', auth()->id()) // Ensure user can only see their own orders
            ->first();

        if (!$data['transaction']) {
            abort(404, 'Pesanan tidak ditemukan.');
        }
        // dd($data['transaction']);

        return view('order_detail', $data);
    }

    public function confirmOrder(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $transaction = Transaction::where('id_transaction', $id)
                ->where('transaction_user_id', auth()->id())
                ->where('transaction_status', 'shipped')
                ->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Pesanan tidak ditemukan atau tidak dapat dikonfirmasi.',
                ], 404);
            }

            $transaction->transaction_status = 'completed';
            $transaction->transaction_updated_by = auth()->id();
            $transaction->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dikonfirmasi sebagai selesai.',
                'data' => [
                    'transaction_status' => $transaction->transaction_status,
                ]
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
                'message' => 'Terjadi kesalahan saat memproses permintaan.',
                'errors' => $e->getMessage(),
            ], 500);
        }
    }
}
