<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class AdminController extends Controller
{
    public function index()
    {
        // Count total products
        $productsCount = Product::count();

        // Count total transactions
        $transactionsCount = DB::table('transactions')->count();

        // Count transactions with status 'completed'
        $transactionsDoneCount = DB::table('transactions')
            ->where('transaction_status', 'completed')
            ->count();

        // Count transactions with status 'shipped'
        $transactionsShippedCount = DB::table('transactions')
            ->where('transaction_status', 'shipped')
            ->count();

        // Count transactions with status 'processed'
        $transactionsProcessedCount = DB::table('transactions')
            ->where('transaction_status', 'processed')
            ->count();

        // Daily revenue
        $dailyRevenue = DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('SUM(total_price) as total'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Daily orders
        $dailyOrders = DB::table('transactions')
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();

        // Get ordered (id, buyer_name, status)
        $transactions = DB::table('transactions')
            ->select('id_transaction', 'tshipping_receipt_name', 'transaction_status')
            ->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->orderBy('created_at', 'desc')
            ->get();

        $popularProducts = DB::table('transaction_details')
            ->select('tdproduct_name as name', 'tdproduct_price as price', DB::raw('SUM(tdproduct_qty) as total_qty'))
            ->groupBy('tdproduct_name', 'tdproduct_price')
            ->orderBy('total_qty', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard',
        compact('transactionsCount', 'productsCount', 'transactionsShippedCount', 'transactionsDoneCount', 'transactionsProcessedCount', 'dailyOrders', 'dailyRevenue', 'transactions', 'popularProducts'));
    }
}
