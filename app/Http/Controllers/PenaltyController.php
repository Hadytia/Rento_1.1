<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PenaltyController extends Controller
{
    /**
     * Display penalties index page.
     */
    public function index()
    {
        // Ambil semua penalties dengan join ke transactions dan users
        $penalties = DB::table('penalties as p')
            ->join('transactions as t', 'p.transaction_id', '=', 't.id')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as pr', 't.product_id', '=', 'pr.id')
            ->where('p.is_deleted', 0)
            ->where('p.status', 1)
            ->select(
                'p.id',
                'p.transaction_id',
                'p.penalty_type',
                'p.penalty_amount',
                'p.overdue_days',
                'p.description',
                'p.resolved',
                'p.created_date',
                't.trx_code',
                't.rental_start',
                't.rental_end',
                't.trx_status',
                'u.name as customer_name',
                'u.email as customer_email',
                'pr.product_name'
            )
            ->orderBy('p.created_date', 'desc')
            ->get();

        // Ambil transaksi overdue yang belum ada penalty (Action Needed)
        $overdueTransactions = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->join('products as pr', 't.product_id', '=', 'pr.id')
            ->leftJoin('penalties as p', 't.id', '=', 'p.transaction_id')
            ->where('t.trx_status', 'Overdue')
            ->where('t.is_deleted', 0)
            ->where('t.status', 1)
            ->select(
                't.id',
                't.trx_code',
                't.rental_end',
                't.total_days',
                'u.name as customer_name',
                'u.email as customer_email',
                'pr.product_name',
                DB::raw("(CURRENT_DATE - t.rental_end::date) as days_late"), // ← fix ini
                DB::raw('COUNT(p.id) as penalty_count')
            )
            ->groupBy(
                't.id', 't.trx_code', 't.rental_end', 't.total_days',
                'u.name', 'u.email', 'pr.product_name'
            )
            ->get();

        // Summary stats
        $stats = [
            'total_penalties'   => DB::table('penalties')->where('is_deleted', 0)->count(),
            'unpaid_penalties'  => DB::table('penalties')->where('resolved', 0)->where('is_deleted', 0)->count(),
            'total_amount'      => DB::table('penalties')->where('is_deleted', 0)->sum('penalty_amount'),
            'unpaid_amount'     => DB::table('penalties')->where('resolved', 0)->where('is_deleted', 0)->sum('penalty_amount'),
        ];

        return view('penalties.index', compact('penalties', 'overdueTransactions', 'stats'));
    }

    /**
     * Mark penalty as resolved (paid).
     */
    public function markResolved($id)
    {
        DB::table('penalties')
            ->where('id', $id)
            ->update([
                'resolved' => 1,
                'status'   => 1,
            ]);

        return redirect()->route('penalties.index')->with('success', 'Penalty marked as resolved.');
    }

    /**
     * Mark penalty as finished / completed (soft approach).
     */
    public function markFinished($id)
    {
        DB::table('penalties')
            ->where('id', $id)
            ->update(['is_deleted' => 1]);

        // Update transaction status jika penalty selesai
        $penalty = DB::table('penalties')->where('id', $id)->first();
        if ($penalty) {
            DB::table('transactions')
                ->where('id', $penalty->transaction_id)
                ->update(['trx_status' => 'Completed']);
        }

        return redirect()->route('penalties.index')->with('success', 'Penalty marked as finished.');
    }

    /**
     * Send email reminder untuk overdue transaction.
     * (Placeholder — integrate dengan Mail/API sesuai kebutuhan)
     */
    public function sendReminder(Request $request)
    {
        $transactionId = $request->input('transaction_id');

        $transaction = DB::table('transactions as t')
            ->join('users as u', 't.user_id', '=', 'u.id')
            ->where('t.id', $transactionId)
            ->select('t.*', 'u.name', 'u.email')
            ->first();

        if (!$transaction) {
            return redirect()->route('penalties.index')->with('error', 'Transaction not found.');
        }

        // TODO: Kirim email via Mail::to($transaction->email)->send(new ReminderMail($transaction));
        // Untuk sekarang hanya log / flash message
        return redirect()->route('penalties.index')->with('success', "Reminder sent to {$transaction->name} ({$transaction->email}).");
    }
}