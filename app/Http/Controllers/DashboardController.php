<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Stat Cards ──
        $totalRevenue = DB::table('transactions')
            ->where('trx_status', 'Completed')
            ->where('is_deleted', 0)
            ->sum('total_amount');

        $activeRentals = DB::table('transactions')
            ->where('trx_status', 'Active')
            ->where('is_deleted', 0)
            ->count();

        $totalCustomers = DB::table('users')
            ->where('is_deleted', 0)
            ->where('status', 1)
            ->count();

        $pendingPenalties = DB::table('penalties')
            ->where('resolved', 0)
            ->where('is_deleted', 0)
            ->count();

        // ── Recent Transactions (5 terbaru) ──
        $recentTransactions = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as p', 't.product_id', '=', 'p.id')
            ->where('t.is_deleted', 0)
            ->select(
                't.trx_code',
                't.total_amount',
                't.trx_status',
                'u.name as customer_name',
                'p.product_name'
            )
            ->orderBy('t.created_date', 'desc')
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalRevenue',
            'activeRentals',
            'totalCustomers',
            'pendingPenalties',
            'recentTransactions'
        ));
    }
}