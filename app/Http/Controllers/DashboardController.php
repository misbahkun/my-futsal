<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

        return view('dashboard.home', compact(
            'formattedTotalNumber', 
            'formattedPercentage', 
            'direction'
        ));
    }

    public function profile() {
        return view('dashboard.profile');
    }
}