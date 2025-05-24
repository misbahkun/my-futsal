<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Transaction;
use Carbon\Carbon;
use Illuminate\Http\Request;
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

    public function searchTransactions(Request $request)
    {
        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Transaction::with('user')->orderBy('created_at', 'desc');

        // Check if user is admin or regular user
        if (Auth::user()->role !== 'admin') {
            $query->where('user_id', Auth::user()->id);
        }

        // Apply search filters if search term exists
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhereHas('user', function ($userQuery) use ($search) {
                        $userQuery->where('full_name', 'like', '%' . $search . '%')
                            ->orWhere('email', 'like', '%' . $search . '%');
                    });

                // Search by date with multiple formats
                $this->addDateSearch($q, $search);
            });
        }

        $transactions = $query->paginate($perPage);

        // Return JSON response for AJAX requests
        if ($request->expectsJson()) {
            return response()->json([
                'data' => $transactions->items(),
                'pagination' => [
                    'current_page' => $transactions->currentPage(),
                    'last_page' => $transactions->lastPage(),
                    'per_page' => $transactions->perPage(),
                    'total' => $transactions->total(),
                    'from' => $transactions->firstItem(),
                    'to' => $transactions->lastItem(),
                ]
            ]);
        }

        return $transactions;
    }

    private function addDateSearch($query, $search)
    {
        // Normalize search input
        $searchLower = strtolower(trim($search));

        // Indonesian month mapping
        $indonesianMonths = [
            'januari' => '01',
            'februari' => '02',
            'maret' => '03',
            'april' => '04',
            'mei' => '05',
            'juni' => '06',
            'juli' => '07',
            'agustus' => '08',
            'september' => '09',
            'oktober' => '10',
            'november' => '11',
            'desember' => '12',
            'jan' => '01',
            'feb' => '02',
            'mar' => '03',
            'apr' => '04',
            'jun' => '06',
            'jul' => '07',
            'agu' => '08',
            'aug' => '08',
            'sep' => '09',
            'okt' => '10',
            'oct' => '10',
            'nov' => '11',
            'des' => '12',
            'dec' => '12'
        ];

        // Replace Indonesian month names with English equivalents for parsing
        $searchForParsing = $searchLower;
        foreach ($indonesianMonths as $indo => $monthNum) {
            if (strpos($searchLower, $indo) !== false) {
                $englishMonths = [
                    'januari' => 'january',
                    'februari' => 'february',
                    'maret' => 'march',
                    'april' => 'april',
                    'mei' => 'may',
                    'juni' => 'june',
                    'juli' => 'july',
                    'agustus' => 'august',
                    'september' => 'september',
                    'oktober' => 'october',
                    'november' => 'november',
                    'desember' => 'december',
                    'jan' => 'jan',
                    'feb' => 'feb',
                    'mar' => 'mar',
                    'apr' => 'apr',
                    'jun' => 'jun',
                    'jul' => 'jul',
                    'agu' => 'aug',
                    'aug' => 'aug',
                    'sep' => 'sep',
                    'okt' => 'oct',
                    'oct' => 'oct',
                    'nov' => 'nov',
                    'des' => 'dec',
                    'dec' => 'dec'
                ];
                $searchForParsing = str_replace($indo, $englishMonths[$indo] ?? $indo, $searchForParsing);
                break;
            }
        }

        // Define date formats to try (in order of priority - most specific first)
        $dateFormats = [
            // Complete date formats (highest priority)
            'd M Y',        // 17 Apr 2025
            'd F Y',        // 17 April 2025  
            'd/m/Y',        // 17/04/2025
            'd-m-Y',        // 17-04-2025
            'Y-m-d',        // 2025-04-17
            'm/d/Y',        // 04/17/2025
            'M d, Y',       // Apr 17, 2025
            'F d, Y',       // April 17, 2025
            'd M',          // 17 Apr (current year)
            'd F',          // 17 April (current year)
            'd/m',          // 17/04 (current year)

            // Month and year formats (medium priority)
            'M Y',          // Apr 2025
            'F Y',          // April 2025
            'm/Y',          // 04/2025
            'm-Y',          // 04-2025

            // Year only (lowest priority)
            'Y',            // 2025
        ];

        $dateMatched = false;

        // Try to parse as exact date formats
        foreach ($dateFormats as $format) {
            try {
                $date = \DateTime::createFromFormat($format, $searchForParsing);

                if ($date !== false && $date->format($format) === $searchForParsing) {
                    $dateMatched = true;

                    switch ($format) {
                        // Complete date - must match exactly
                        case 'd M Y':
                        case 'd F Y':
                        case 'd/m/Y':
                        case 'd-m-Y':
                        case 'Y-m-d':
                        case 'm/d/Y':
                        case 'M d, Y':
                        case 'F d, Y':
                            $query->orWhereDate('created_at', $date->format('Y-m-d'));
                            break;

                        // Day and month in current year
                        case 'd M':
                        case 'd F':
                        case 'd/m':
                            $currentYear = date('Y');
                            $query->orWhere(function ($q) use ($date, $currentYear) {
                                $q->whereDay('created_at', $date->format('d'))
                                    ->whereMonth('created_at', $date->format('m'))
                                    ->whereYear('created_at', $currentYear);
                            });
                            break;

                        // Month and year - match all dates in that month
                        case 'M Y':
                        case 'F Y':
                        case 'm/Y':
                        case 'm-Y':
                            $query->orWhere(function ($q) use ($date) {
                                $q->whereMonth('created_at', $date->format('m'))
                                    ->whereYear('created_at', $date->format('Y'));
                            });
                            break;

                        // Year only - match all dates in that year
                        case 'Y':
                            $query->orWhereYear('created_at', $date->format('Y'));
                            break;
                    }

                    return; // Exit after first successful match
                }
            } catch (\Exception $e) {
                continue; // Try next format
            }
        }

        // If no standard format matched, try some special cases
        if (!$dateMatched) {
            // Check if it's just a month name (Indonesian or English)
            foreach ($indonesianMonths as $monthName => $monthNumber) {
                if ($searchLower === $monthName) {
                    $currentYear = date('Y');
                    $query->orWhere(function ($q) use ($monthNumber, $currentYear) {
                        $q->whereMonth('created_at', $monthNumber)
                            ->whereYear('created_at', $currentYear);
                    });
                    return;
                }
            }

            // Check if it's a pure number
            if (is_numeric($search)) {
                $number = (int)$search;

                if (strlen($search) == 4 && $number >= 1900 && $number <= 2100) {
                    // 4 digits - assume it's a year
                    $query->orWhereYear('created_at', $number);
                } elseif ($number >= 1 && $number <= 31) {
                    // 1-2 digits between 1-31 - assume it's a day in current month/year
                    $currentMonth = date('m');
                    $currentYear = date('Y');
                    $query->orWhere(function ($q) use ($number, $currentMonth, $currentYear) {
                        $q->whereDay('created_at', $number)
                            ->whereMonth('created_at', $currentMonth)
                            ->whereYear('created_at', $currentYear);
                    });
                } elseif ($number >= 1 && $number <= 12) {
                    // 1-2 digits between 1-12 - could be month in current year
                    $currentYear = date('Y');
                    $query->orWhere(function ($q) use ($number, $currentYear) {
                        $q->whereMonth('created_at', $number)
                            ->whereYear('created_at', $currentYear);
                    });
                }
                return;
            }
        }

        // Last resort: search in formatted date strings (but be more restrictive)
        if (!$dateMatched) {
            // Only search if the input looks like it could be a date
            if (preg_match('/\d/', $search)) {
                $query->orWhereRaw("DATE_FORMAT(created_at, '%d %M %Y') = ?", [$search])
                    ->orWhereRaw("DATE_FORMAT(created_at, '%d/%m/%Y') = ?", [$search])
                    ->orWhereRaw("DATE_FORMAT(created_at, '%Y-%m-%d') = ?", [$search]);
            }
        }
    }
}
