<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            // using datatable ajax
            $query = Transaction::query()
                ->with(['user', 'details']);

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
            // dd($query->toSql(), $query->getBindings());

            return $datatables
                ->addIndexColumn()
                ->addColumn('tshipping_receipt_name', function (Transaction $transaction) {
                    return "<div class='px-4 py-2'>"
                        . $transaction->tshipping_receipt_name ?? '-' . "</div>";
                })
                ->addColumn('created_at', function (Transaction $transaction) {
                    return "<div class='px-4 py-2 text-center'>"
                        . $transaction->created_at->translatedFormat('d F Y') . "</div>";
                })
                ->addColumn('details_count', function (Transaction $transaction) {
                    return "<div class='px-4 py-2 text-center'>"
                        . $transaction->details->sum('tdproduct_qty') . "</div>";
                })
                ->addColumn('total_price', function (Transaction $transaction) {
                    return "<div class='px-4 py-2'>"
                        . 'Rp. ' . number_format($transaction->total_price, 0, ',', '.') . "</div>";
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
                    <a href="' . route('admin.transactions.show', $transaction->id_transaction) . '" class="flex items-center justify-center w-24 px-3 py-1 text-black transition border border-black rounded hover:bg-gray-200"
                        title="Preview">
                        <span class="mr-1 ti ti-brand-google-podcasts"></span>
                        Lihat
                    </a>
                </div>';
                })
                ->rawColumns(['tshipping_receipt_name', 'created_at', 'details_count', 'total_price', 'status', 'action'])
                ->make(true);
        }

        return view('admin.transactions.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.transactions.detail');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['transaction'] = Transaction::with(['user', 'details'])
            ->where('id_transaction', $id)
            ->first();

        return view('admin.transactions.detail', $data);
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
        DB::beginTransaction();
        try {
            $rules = [
                'transaction_status' => 'required|in:processed,shipped,completed,cancelled',
                'tshipping_proof' => 'nullable|image|max:2048',
                'tshipping_tracking_number' => 'nullable|required_with:tshipping_proof|string|max:255',
            ];

            $messages = [
                'transaction_status.required' => 'Status transaksi harus diisi.',
                'transaction_status.in' => 'Status transaksi tidak valid.',
                'tshipping_proof.image' => 'Bukti pengiriman harus berupa gambar.',
                'tshipping_proof.max' => 'Ukuran bukti pengiriman maksimal 2MB.',
                'tshipping_tracking_number.max' => 'Nomor resi maksimal 255 karakter.',
                'tshipping_tracking_number.required_with' => 'Nomor resi harus diisi jika bukti pengiriman ada.',
            ];
            $request->validate($rules, $messages);

            $transaction = Transaction::find($id);
            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaksi tidak ditemukan.',
                ], 404);
            }

            $transaction->transaction_status = $request->transaction_status;
            if ($request->hasFile('tshipping_proof')) {
                $transaction->tshipping_proof = $request->file('tshipping_proof')->store('tshipping_proofs', 'public');
            }
            $transaction->tshipping_tracking_number = $request->tshipping_tracking_number;
            $transaction->transaction_updated_by = auth()->id();
            $transaction->save();
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil diperbarui.',
                'data' => [
                    'transaction_status' => $transaction->transaction_status,
                    'tracking_number' => $transaction->tshipping_tracking_number,
                    'transaction_pay_proof' => $transaction->tshipping_proof ? asset('storage/' . $transaction->tshipping_proof) : null,
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
