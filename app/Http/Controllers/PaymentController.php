<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;

class PaymentController extends Controller
{
    public function handleMidtransCallback(Request $request)
    {
        try {
            // Log the raw request to debug
            Log::info('Midtrans Callback Raw Request:', $request->all());

            // Initialize Midtrans Notification
            $notif = new Notification();

            // Log notification data
            Log::info('Midtrans Notification Data:', [
                'order_id' => $notif->order_id,
                'transaction_status' => $notif->transaction_status,
                'payment_type' => $notif->payment_type,
                'fraud_status' => $notif->fraud_status,
            ]);

            // Find the transaction
            $transaction = Transaction::where('midtrans_order_id', $notif->order_id)->firstOrFail();

            $status = $notif->transaction_status;
            $type = $notif->payment_type;
            $fraud = $notif->fraud_status;

            if ($status == 'capture') {
                if ($fraud == 'accept') {
                    $transaction->update([
                        'status' => 'paid',
                        'midtrans_status' => $status,
                        'payment_type' => $type
                    ]);
                }
            } elseif ($status == 'settlement') {
                $transaction->update([
                    'status' => 'paid',
                    'midtrans_status' => $status,
                    'payment_type' => $type
                ]);
            } elseif ($status == 'pending') {
                $transaction->update([
                    'status' => 'unpaid',
                    'midtrans_status' => $status,
                    'payment_type' => $type
                ]);
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $transaction->update([
                    'status' => 'cancelled',
                    'midtrans_status' => $status,
                    'payment_type' => $type
                ]);
                Schedule::where('transaction_id', $transaction->id)->delete();
            } else {
                Log::warning("Unhandled transaction status: {$status} for order_id: {$notif->order_id}");
            }

            Log::info("Midtrans callback processed for order_id: {$notif->order_id}, status: {$status}");
            return response()->json(['status' => 'success']);
        } catch (\Exception $e) {
            Log::error("Midtrans callback error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}
