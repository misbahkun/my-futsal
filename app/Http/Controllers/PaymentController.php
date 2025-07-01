<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Midtrans\Notification;
use Midtrans\Config;

class PaymentController extends Controller
{
    public function handleMidtransCallback(Request $request)
    {
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$clientKey = config('midtrans.client_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = config('midtrans.is_sanitized');
            Config::$is3ds = config('midtrans.is_3ds');

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
                'notif' => $notif
            ]);

            // Find the transaction
            $transaction = Transaction::where('midtrans_order_id', $notif->order_id)->firstOrFail();

            $status = $notif->transaction_status;
            $type = $notif->payment_type;
            $fraud = $notif->fraud_status;

            $vaNumbers = $request->input('va_numbers', []);
            $vaBank = !empty($vaNumbers) ? $vaNumbers[0]['bank'] : 'unknown';

            if ($status == 'capture') {
                if ($fraud == 'accept') {
                    $transaction->update([
                        'status' => 'paid',                        
                        'payment_type' => $type
                    ]);

                    if ($vaBank != 'unknown') {
                        $transaction->update([
                            'payment_method' => "va " . $vaBank
                        ]);
                    }
                }
            } elseif ($status == 'settlement') {
                $transaction->update([
                    'status' => 'paid',                    
                    'payment_type' => $type
                ]);

                if ($vaBank != 'unknown') {
                    $transaction->update([
                        'payment_method' => "va " . $vaBank
                    ]);
                }
            } elseif ($status == 'pending') {
                $transaction->update([
                    'status' => 'unpaid',
                    'payment_type' => $type
                ]);
                if ($vaBank != 'unknown') {
                    $transaction->update([
                        'payment_method' => "va " . $vaBank
                    ]);
                }
            } elseif (in_array($status, ['deny', 'expire', 'cancel'])) {
                $transaction->update([
                    'status' => 'cancelled',
                    'payment_type' => $type
                ]);
                if ($vaBank != 'unknown') {
                    $transaction->update([
                        'payment_method' => "va " . $vaBank
                    ]);
                }
                Schedule::where('transaction_id', $transaction->id)->delete();
                Log::info("Schedule cancelled {$transaction->id}");
            } else {
                Log::warning("Unhandled transaction status: {$status} for order_id: {$notif->order_id}");
            }
            Log::info("Midtrans callback processed for order_id: {$notif->order_id}, status: {$status}");
            return response()->json(['status' => 'success'], 200);
        } catch (\Exception $e) {
            Log::error("Midtrans callback error: " . $e->getMessage());
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }
}