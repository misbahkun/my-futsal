<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices with filters.
     */
    public function index(Request $request)
    {
        try {
            $query = Transaction::where('status', 'paid')
                ->with('user');

            // Check if the user is not an admin, filter by user_id
            if (Auth::user()->role !== 'admin') {
                $query->where('user_id', Auth::id());
            }

            // Apply date filters with simplified logic
            if ($request->filled('filter_type')) {
                $filterType = $request->filter_type;
                $now = Carbon::now();

                switch ($filterType) {
                    case 'day':
                        // Filter by today
                        $query->whereDate('created_at', $now->toDateString());
                        break;

                    case 'week':
                        // Filter by this week (Monday to Sunday)
                        $startOfWeek = $now->startOfWeek();
                        $endOfWeek = $now->copy()->endOfWeek();
                        $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                        break;

                    case 'month':
                        // Filter by this month
                        $query->whereYear('created_at', $now->year)
                            ->whereMonth('created_at', $now->month);
                        break;

                    case 'year':
                        // Filter by this year
                        $query->whereYear('created_at', $now->year);
                        break;
                }
            }

            // Apply payment method filter
            if ($request->filled('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            // Apply amount range filter (new dropdown logic)
            if ($request->filled('amount_range')) {
                $range = explode('-', $request->amount_range);
                if (count($range) == 2) {
                    $minAmount = (int) $range[0];
                    $maxAmount = (int) $range[1];
                    $query->whereBetween('amount', [$minAmount, $maxAmount]);
                }
            }

            // Fallback to individual min/max amount filters if provided
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }

            $invoices = $query->orderBy('created_at', 'desc')->paginate(10);

            // Calculate summary statistics for the filtered results
            $totalAmount = Transaction::where('status', 'paid')
                ->when(Auth::user()->role !== 'admin', function ($q) {
                    return $q->where('user_id', Auth::id());
                })
                ->when($request->filled('filter_type'), function ($q) use ($request) {
                    $filterType = $request->filter_type;
                    $now = Carbon::now();

                    switch ($filterType) {
                        case 'day':
                            return $q->whereDate('created_at', $now->toDateString());
                        case 'week':
                            $startOfWeek = $now->startOfWeek();
                            $endOfWeek = $now->copy()->endOfWeek();
                            return $q->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                        case 'month':
                            return $q->whereYear('created_at', $now->year)
                                ->whereMonth('created_at', $now->month);
                        case 'year':
                            return $q->whereYear('created_at', $now->year);
                    }
                    return $q;
                })
                ->when($request->filled('payment_method') && $request->payment_method !== 'all', function ($q) use ($request) {
                    return $q->where('payment_method', $request->payment_method);
                })
                ->when($request->filled('amount_range'), function ($q) use ($request) {
                    $range = explode('-', $request->amount_range);
                    if (count($range) == 2) {
                        $minAmount = (int) $range[0];
                        $maxAmount = (int) $range[1];
                        return $q->whereBetween('amount', [$minAmount, $maxAmount]);
                    }
                    return $q;
                })
                ->when($request->filled('min_amount'), function ($q) use ($request) {
                    return $q->where('amount', '>=', $request->min_amount);
                })
                ->when($request->filled('max_amount'), function ($q) use ($request) {
                    return $q->where('amount', '<=', $request->max_amount);
                })
                ->sum('amount');

            $totalCount = $query->count();

            return view('dashboard.invoices.index', compact('invoices', 'totalAmount', 'totalCount'));
        } catch (\Exception $e) {
            Log::error("Error retrieving invoices: " . $e->getMessage());
            return redirect()->route('homeDashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar invoice: ' . $e->getMessage());
        }
    }

    /**
     * Export invoices to Excel
     */
    public function exportExcel(Request $request)
    {
        try {
            $query = Transaction::where('status', 'paid')
                ->with('user');

            // Check if the user is not an admin, filter by user_id
            if (Auth::user()->role !== 'admin') {
                $query->where('user_id', Auth::id());
            }

            // Apply the same filters as in index method
            if ($request->filled('filter_type')) {
                $filterType = $request->filter_type;
                $now = Carbon::now();

                switch ($filterType) {
                    case 'day':
                        $query->whereDate('created_at', $now->toDateString());
                        break;
                    case 'week':
                        $startOfWeek = $now->startOfWeek();
                        $endOfWeek = $now->copy()->endOfWeek();
                        $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                        break;
                    case 'month':
                        $query->whereYear('created_at', $now->year)
                            ->whereMonth('created_at', $now->month);
                        break;
                    case 'year':
                        $query->whereYear('created_at', $now->year);
                        break;
                }
            }

            if ($request->filled('payment_method') && $request->payment_method !== 'all') {
                $query->where('payment_method', $request->payment_method);
            }

            // Apply amount range filter
            if ($request->filled('amount_range')) {
                $range = explode('-', $request->amount_range);
                if (count($range) == 2) {
                    $minAmount = (int) $range[0];
                    $maxAmount = (int) $range[1];
                    $query->whereBetween('amount', [$minAmount, $maxAmount]);
                }
            }

            // Fallback filters
            if ($request->filled('min_amount')) {
                $query->where('amount', '>=', $request->min_amount);
            }
            if ($request->filled('max_amount')) {
                $query->where('amount', '<=', $request->max_amount);
            }

            $invoices = $query->orderBy('created_at', 'desc')->get();

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set headers
            $headers = ['No. Invoice', 'Tanggal', 'Total', 'Metode Pembayaran'];
            if (Auth::user()->role === 'admin') {
                $headers[] = 'User';
            }
            $headers[] = 'Status';

            // Write headers with styling
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->getFont()->setBold(true);
                $sheet->getStyle($column . '1')->getFill()->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID);
                $sheet->getStyle($column . '1')->getFill()->getStartColor()->setARGB('FF4CAF50');
                $sheet->getStyle($column . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Write data
            $row = 2;
            foreach ($invoices as $invoice) {
                $invoiceNumber = 'INV-' . $invoice->id . '-' . date('Ymd', strtotime($invoice->created_at));
                $date = date('d F Y', strtotime($invoice->updated_at));
                $amount = 'Rp' . number_format($invoice->amount, 0, ',', '.');
                $paymentMethod = ucwords(str_replace('_', ' ', $invoice->payment_method));

                $sheet->setCellValue('A' . $row, $invoiceNumber);
                $sheet->setCellValue('B' . $row, $date);
                $sheet->setCellValue('C' . $row, $amount);
                $sheet->setCellValue('D' . $row, $paymentMethod);

                $col = 'E';
                if (Auth::user()->role === 'admin') {
                    $sheet->setCellValue($col . $row, $invoice->user->full_name ?? 'N/A');
                    $col = 'F';
                }
                $sheet->setCellValue($col . $row, ucfirst($invoice->status));

                $row++;
            }

            // Add summary at the bottom with styling
            $row += 2;
            $sheet->setCellValue('A' . $row, 'RINGKASAN EXPORT');
            $sheet->getStyle('A' . $row)->getFont()->setBold(true);
            $sheet->getStyle('A' . $row)->getFont()->setSize(12);

            $row++;
            $sheet->setCellValue('A' . $row, 'Total Invoices:');
            $sheet->setCellValue('B' . $row, count($invoices));
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

            $row++;
            $sheet->setCellValue('A' . $row, 'Total Amount:');
            $sheet->setCellValue('B' . $row, 'Rp' . number_format($invoices->sum('amount'), 0, ',', '.'));
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

            $row++;
            $sheet->setCellValue('A' . $row, 'Export Date:');
            $sheet->setCellValue('B' . $row, date('d F Y H:i:s'));
            $sheet->getStyle('A' . $row . ':B' . $row)->getFont()->setBold(true);

            // Create writer and download
            $writer = new Xlsx($spreadsheet);
            $fileName = 'invoices_export_' . date('Y-m-d_H-i-s') . '.xlsx';
            $tempFile = tempnam(sys_get_temp_dir(), $fileName);
            $writer->save($tempFile);

            return response()->download($tempFile, $fileName, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ])->deleteFileAfterSend(true);
        } catch (\Exception $e) {
            Log::error("Error exporting invoices to Excel: " . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Terjadi kesalahan saat mengekspor data: ' . $e->getMessage());
        }
    }

    /**
     * Show invoice for completed booking
     */
    public function showInvoice($transactionId)
    {
        try {
            // Find the transaction with related data
            $transaction = Transaction::with('user')
                ->findOrFail($transactionId);

            // Make sure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke invoice ini.');
            }

            // Check if transaction is paid
            if ($transaction->status !== 'paid') {
                return redirect()->route('payment.pending', $transaction->id)
                    ->with('error', 'Transaksi ini belum dibayar. Silakan selesaikan pembayaran terlebih dahulu.');
            }

            // Get scheduled items from database
            $schedules = Schedule::where('transaction_id', $transaction->id)
                ->with(['field.venue'])
                ->get();

            $discountPercentage = 0;
            $totalOriginalPrice = 0;

            // Check if user is eligible for discount
            $hasDiscount = Auth::check() && Auth::user()->role !== 'non_member';
            if ($hasDiscount) {
                $discountPercentage = 10;
            }

            return view('invoice', compact('transaction', 'schedules'));
        } catch (\Exception $e) {
            Log::error("Error in showInvoice: " . $e->getMessage());
            return redirect()->route('home')
                ->with('error', 'Terjadi kesalahan saat memuat invoice: ' . $e->getMessage());
        }
    }

    /**
     * Generate PDF version of invoice with A4 paper size
     */
    public function generateInvoicePdf($transactionId)
    {
        try {
            // Find the transaction with related data
            $transaction = Transaction::with('user')
                ->findOrFail($transactionId);

            // Make sure the transaction belongs to the current user
            if ($transaction->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
                return redirect()->route('home')
                    ->with('error', 'Anda tidak memiliki akses ke invoice ini.');
            }

            // Check if transaction is paid
            if ($transaction->status !== 'paid') {
                return redirect()->route('payment.pending', $transaction->id)
                    ->with('error', 'Transaksi ini belum dibayar. Silakan selesaikan pembayaran terlebih dahulu.');
            }

            // Get scheduled items from database
            $schedules = Schedule::where('transaction_id', $transaction->id)
                ->with(['field.venue'])
                ->get();

            $pdf = Pdf::loadView('invoice-pdf', compact('transaction', 'schedules'));
            $pdf->setPaper('a4', 'portrait');

            // Set nama file yang akan di-download
            $filename = 'Invoice-' . $transaction->id . '-' . date('Ymd') . '.pdf';

            // Unduh file
            return $pdf->download($filename);
        } catch (\Exception $e) {
            Log::error("Error in generateInvoicePdf: " . $e->getMessage());
            return redirect()->route('invoice', $transaction->id)
                ->with('error', 'Terjadi kesalahan saat menghasilkan PDF: ' . $e->getMessage());
        }
    }
}