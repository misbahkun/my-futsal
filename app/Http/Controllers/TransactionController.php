<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MyAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $banks = MyAccount::select('name')->get();
        $users = User::all();

        // Handle initial load or non-AJAX requests
        $transactions = Transaction::with('user')->latest()->paginate(10);

        return view('dashboard.transactions.index', compact('transactions', 'users', 'banks'));
    }

    /**
     * Search transactions with AJAX.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $search = $request->get('search', '');
        $perPage = $request->get('per_page', 10);

        $query = Transaction::with('user')->orderBy('created_at', 'desc');

        // Apply search filters if search term exists
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('id', 'like', '%' . $search . '%')
                    ->orWhere('amount', 'like', '%' . $search . '%')
                    ->orWhere('status', 'like', '%' . $search . '%')
                    ->orWhere('payment_type', 'like', '%' . $search . '%')
                    ->orWhere('payment_method', 'like', '%' . $search . '%')
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

    /**
     * Add date search logic to the query.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return void
     */
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

    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::with(['user', 'schedule'])->findOrFail($id);
        return view('dashboard.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::findOrFail($id);
        $users = User::all();
        $banks = MyAccount::select('name')->get();
        return view('dashboard.transactions.edit', compact('transaction', 'users', 'banks'));
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:online,on_site',
            'payment_method' => 'required|string',
            'status' => 'required|in:unpaid,pending_verification,paid,cancelled',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Update transaction
        $transaction = Transaction::findOrFail($id);

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if it exists
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            $file = $request->file('payment_proof');
            $paymentProofPath = $file->store('payment_proofs', 'public');

            $transaction->payment_proof = $paymentProofPath;
        }

        $transaction->user_id = $request->user_id;
        $transaction->amount = $request->amount;
        $transaction->payment_type = $request->payment_type;
        $transaction->payment_method = $request->payment_method;
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.transactions')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::findOrFail($id);

        // Delete payment proof file if it exists
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $transaction->delete();

        return response()->json(['success' => 'Transaction deleted successfully.']);
    }
}
