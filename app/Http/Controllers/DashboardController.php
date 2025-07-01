<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Field;
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

    public function scheduleChartData(Request $request)
    {
        try {
            // Get date range (default to last 7 days)
            $startDate = $request->input('start') ?
                Carbon::parse($request->input('start'))->startOfDay() :
                Carbon::now()->subDays(6)->startOfDay();

            $endDate = $request->input('end') ?
                Carbon::parse($request->input('end'))->endOfDay() :
                Carbon::now()->endOfDay();

            // Ensure we don't exceed 7 days
            if ($startDate->diffInDays($endDate) > 6) {
                $startDate = $endDate->copy()->subDays(6)->startOfDay();
            }

            // Log for debugging
            Log::info('Chart Date Range:', [
                'start' => $startDate->format('Y-m-d H:i:s'),
                'end' => $endDate->format('Y-m-d H:i:s')
            ]);

            // Generate date labels
            $dates = [];
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dates[] = $currentDate->format('d M Y');
                $currentDate->addDay();
            }

            // Get all fields
            $fields = Field::orderBy('name')->get();

            // Initialize datasets for each field
            $datasets = [];
            $colors = [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384',
                '#C9CBCF'
            ];

            foreach ($fields as $index => $field) {
                $fieldData = [];
                $currentDate = $startDate->copy();

                // Get data for each date
                while ($currentDate->lte($endDate)) {
                    $dayStart = $currentDate->copy()->startOfDay();
                    $dayEnd = $currentDate->copy()->endOfDay();

                    // Query berdasarkan tanggal transaksi
                    $query = Schedule::whereBetween('created_at', [
                        $dayStart->format('Y-m-d H:i:s'),
                        $dayEnd->format('Y-m-d H:i:s')
                    ])
                        ->where('field_id', $field->id);

                    // Filter by user if not admin
                    if (Auth::user()->role !== 'admin') {
                        $query->whereHas('transaction', function ($q) {
                            $q->where('user_id', Auth::user()->id);
                        });
                    }

                    $count = $query->count();
                    $fieldData[] = $count;

                    $currentDate->addDay(); // Lanjutkan iterasi tanggal
                }

                $datasets[] = [
                    'label' => $field->name,
                    'data' => $fieldData,
                    'backgroundColor' => $colors[$index % count($colors)] . '80',
                    'borderColor' => $colors[$index % count($colors)],
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4
                ];
            }

            // Calculate totals for summary
            $totalSchedules = collect($datasets)->sum(function ($dataset) {
                return array_sum($dataset['data']);
            });

            // Get most popular field
            $fieldTotals = collect($datasets)->map(function ($dataset) {
                return [
                    'field' => $dataset['label'],
                    'total' => array_sum($dataset['data'])
                ];
            })->sortByDesc('total');

            $mostPopularField = $fieldTotals->first();

            return response()->json([
                'labels' => $dates,
                'datasets' => $datasets,
                'summary' => [
                    'total_schedules' => $totalSchedules,
                    'most_popular_field' => $mostPopularField,
                    'date_range' => [
                        'start' => $startDate->format('d M Y'),
                        'end' => $endDate->format('d M Y')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Chart Data Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to load chart data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    // Alternative: Query dengan join ke tabel transaction untuk lebih akurat
    public function scheduleChartDataWithJoin(Request $request)
    {
        try {
            // Get date range (default to last 7 days)
            $startDate = $request->input('start') ?
                Carbon::parse($request->input('start'))->startOfDay() :
                Carbon::now()->subDays(6)->startOfDay();

            $endDate = $request->input('end') ?
                Carbon::parse($request->input('end'))->endOfDay() :
                Carbon::now()->endOfDay();

            // Ensure we don't exceed 7 days
            if ($startDate->diffInDays($endDate) > 6) {
                $startDate = $endDate->copy()->subDays(6)->startOfDay();
            }

            // Generate date labels
            $dates = [];
            $currentDate = $startDate->copy();
            while ($currentDate->lte($endDate)) {
                $dates[] = $currentDate->format('d M');
                $currentDate->addDay();
            }

            // Get all fields
            $fields = Field::orderBy('name')->get();

            // Initialize datasets for each field
            $datasets = [];
            $colors = [
                '#FF6384',
                '#36A2EB',
                '#FFCE56',
                '#4BC0C0',
                '#9966FF',
                '#FF9F40',
                '#FF6384',
                '#C9CBCF'
            ];

            foreach ($fields as $index => $field) {
                $fieldData = [];

                // Get data for each date
                $currentDate = $startDate->copy();
                while ($currentDate->lte($endDate)) {
                    $dayStart = $currentDate->copy()->startOfDay();
                    $dayEnd = $currentDate->copy()->endOfDay();

                    // Join dengan tabel transaction untuk menggunakan tanggal transaksi
                    $query = Schedule::join('transactions', 'schedules.transaction_id', '=', 'transactions.id')
                        ->whereBetween('transactions.created_at', [
                            $dayStart->format('Y-m-d H:i:s'),
                            $dayEnd->format('Y-m-d H:i:s')
                        ])
                        ->where('schedules.field_id', $field->id);

                    // Filter by user if not admin
                    if (Auth::user()->role !== 'admin') {
                        $query->where('transactions.user_id', Auth::user()->id);
                    }

                    $count = $query->count();
                    $fieldData[] = $count;

                    $currentDate->addDay();
                }

                $datasets[] = [
                    'label' => $field->name,
                    'data' => $fieldData,
                    'backgroundColor' => $colors[$index % count($colors)] . '80',
                    'borderColor' => $colors[$index % count($colors)],
                    'borderWidth' => 2,
                    'fill' => false,
                    'tension' => 0.4
                ];
            }

            // Calculate totals for summary
            $totalSchedules = collect($datasets)->sum(function ($dataset) {
                return array_sum($dataset['data']);
            });

            // Get most popular field
            $fieldTotals = collect($datasets)->map(function ($dataset) {
                return [
                    'field' => $dataset['label'],
                    'total' => array_sum($dataset['data'])
                ];
            })->sortByDesc('total');

            $mostPopularField = $fieldTotals->first();

            return response()->json([
                'labels' => $dates,
                'datasets' => $datasets,
                'summary' => [
                    'total_schedules' => $totalSchedules,
                    'most_popular_field' => $mostPopularField,
                    'date_range' => [
                        'start' => $startDate->format('d M Y'),
                        'end' => $endDate->format('d M Y')
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Chart Data Error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to load chart data',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getBookingDetailsForDate(Request $request)
    {
        // Validasi input tanggal
        $request->validate([
            'date' => 'required|date_format:d M Y',
        ]);

        try {
            // Parse tanggal dari format 'd M Y' (contoh: 02 Jul 2025) ke objek Carbon
            $parsedDate = Carbon::createFromFormat('d M Y', $request->input('date'))->startOfDay();

            // Query untuk mengambil jadwal pada tanggal tersebut
            $query = Schedule::with('field')
                ->whereDate('created_at', $parsedDate);

            // Filter berdasarkan user jika bukan admin
            if (Auth::user()->role !== 'admin') {
                $query->whereHas('transaction', function ($q) {
                    $q->where('user_id', Auth::id());
                });
            }

            $schedules = $query->orderBy('field_id')->get();

            // Jika tidak ada jadwal, kembalikan array kosong
            if ($schedules->isEmpty()) {
                return response()->json([]);
            }

            // Kelompokkan hasil berdasarkan nama lapangan
            $groupedByField = $schedules->groupBy('field.name');

            // Format data untuk respons JSON
            $details = $groupedByField->map(function ($schedulesInField, $fieldName) {
                return [
                    'field_name' => $fieldName,
                    'bookings' => $schedulesInField->map(function ($schedule) {
                        return [
                            'time_slots' => $schedule->time_slots,
                        ];
                    }),
                    'total_bookings' => $schedulesInField->count()
                ];
            })->values(); // ->values() untuk mereset key menjadi array 0, 1, 2, ...

            return response()->json($details);
        } catch (\Exception $e) {
            Log::error('Error fetching booking details by date: ' . $e->getMessage());
            return response()->json(['error' => 'Gagal mengambil data detail.'], 500);
        }
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
