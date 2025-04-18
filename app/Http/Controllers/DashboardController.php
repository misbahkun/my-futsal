<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $totalUser = User::count();
        $formattedTotalNumber = number_format($totalUser, 0, ',', '.');

        $now = Carbon::now();
        $usersThisMonth = User::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $lastMonth = Carbon::now()->subMonth();
        $usersLastMonth = User::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($usersLastMonth > 0) {
            $percentageChange = (($usersThisMonth - $usersLastMonth) / $usersLastMonth) * 100;
        } else {
            $percentageChange = $usersThisMonth > 0 ? 100 : 0;
        }

        $formattedPercentage = number_format(abs($percentageChange), 2);
        $direction = $percentageChange >= 0 ? 'up' : 'down';

        $totalTransactions = Transaction::where('user_id', '=', Auth::user()->id)->count();
        $allTotalTransactions = Transaction::count();

        Log::info(['totalTransactions' => $totalTransactions, 'allTotalTransactions' => $allTotalTransactions]);

        $transactionsThisMonth = Transaction::whereMonth('created_at', $now->month)
            ->whereYear('created_at', $now->year)
            ->count();

        $transactionsLastMonth = Transaction::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->count();

        if ($transactionsLastMonth > 0) {
            $transactionPercentageChange = (($transactionsThisMonth - $transactionsLastMonth) / $transactionsLastMonth) * 100;
        } else {
            $transactionPercentageChange = $transactionsThisMonth > 0 ? 100 : 0;
        }

        $transactionPercentage = number_format(abs($transactionPercentageChange), 2);
        $transactionDirection = $transactionPercentageChange >= 0 ? 'up' : 'down';

        // Recent transactions with pagination - updated to use paginate(10)
        $historyTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->where('user_id', '=', Auth::user()->id)
            ->paginate(10);

        $allHistoryTransactions = Transaction::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('dashboard.home', compact(
            'formattedTotalNumber',
            'formattedPercentage',
            'direction',
            'allTotalTransactions',
            'totalTransactions',
            'transactionPercentage',
            'transactionDirection',
            'allHistoryTransactions',
            'historyTransactions'
        ));
    }
}