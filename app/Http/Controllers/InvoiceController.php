<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Transaction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the invoices.
     */
    public function index()
    {
        try {
            // Check if the user is an admin
            if (Auth::user()->role === 'admin') {
                // If admin, get all paid transactions
                $invoices = Transaction::where('status', 'paid')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            } else {
                // If regular user, get only their paid transactions
                $invoices = Transaction::where('user_id', Auth::id())
                    ->where('status', 'paid')
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);
            }

            return view('dashboard.invoices.index', compact('invoices'));
        } catch (\Exception $e) {
            Log::error("Error retrieving invoices: " . $e->getMessage());
            return redirect()->route('homeDashboard')
                ->with('error', 'Terjadi kesalahan saat memuat daftar invoice: ' . $e->getMessage());
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