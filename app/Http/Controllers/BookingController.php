<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Transaction;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
  public function index() {
    return view('payment');
  }

  public function book(Request $request)
  {
    try {
      $items = $request->input('items');

      Log::info(['req input' => $items]);

      foreach ($items as $item) {
        $item = json_decode($item, true);
        // get only date
        $date = explode(' ', $item['date'])[0] . join([' 00:00:00']);
        // get only time slot
        $time_slot = explode(' ', $item['date'], 3)[1] . ' ' . explode(' ', $item['date'], 3)[2];

        Schedule::upsert([
          'date' => $date,
          'time_slots' => $time_slot,
          'status' => 'booked',
          'price' => $item['price'],
          'field_id' => $item['field_id'],
          'user_id' => Auth::id()
        ], ['date', 'time_slots', 'field_id']);

        $schedule = Schedule::where('field_id', $item['field_id'])
          ->where('date', $date)
          ->where('time_slots', $time_slot)
          ->first();

        Log::info(['data schedule' => $schedule]);
        Log::info(['user id' => Auth::id()]);

        $transaction = Transaction::create([
          'user_id' => Auth::id(),
          'schedule_id' => $schedule->id,
          'amount' => $item['price'],
          'status' => 'unpaid',
          'payment_method' => $item['payment_method']
        ]);

        $schedule->update(['status' => 'booked']);
      }

      return redirect()->route('payment', ['transactionId' => $transaction->id])
        ->with('success', 'Booking berhasil. Silakan lakukan pembayaran.');
    } catch (Exception $e) {
      Log::error("Booking error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return back()->with('error', 'Terjadi kesalahan saat melakukan booking: ' . $e->getMessage());
    }
  }

  public function showPaymentConfirmation($transactionId)
{
    // Verify the transaction belongs to the current user
    $transaction = Transaction::where('id', $transactionId)
        ->where('user_id', Auth::id())
        ->firstOrFail();

    return view('payment', compact('transaction'));
}

  public function uploadPayment(Request $request, $transactionId)
  {
    try {
      $request->validate([
        'payment_proof' => 'required|image|mimes:jpeg,png,jpg|max:10999'
      ]);

      $transaction = Transaction::findOrFail($transactionId);

      if ($request->hasFile('payment_proof')) {
        $file = $request->file('payment_proof');
        $fileName = $file->getClientOriginalName();
        $path = $file->store($fileName, 'public');

        $transaction->update([
          'payment_proof' => $path
        ]);
      }

      return back()->with('success', 'Bukti pembayaran berhasil diunggah');
    } catch (Exception $e) {
      Log::error("Error in uploadPayment: " . $e->getMessage());
      return back()->with('error', 'Terjadi kesalahan saat mengonfirmasi pembayaran');
    }
  }
}
