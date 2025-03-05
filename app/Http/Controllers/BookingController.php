<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
  public function book(Request $request)
  {
    $items = $request->input('items');

    Log::info(['req input' => $items]);

    foreach ($items as $item) {
      $item = json_decode($item, true);
      // get only date
      $date = explode(' ', $item['date'])[0] . join([' 00:00:00']);
      // get only time slot
      $time_slot = explode(' ', $item['date'], 3)[1] . ' ' . explode(' ', $item['date'], 3)[2];

      $schedule = Schedule::create([
        'date' => $date,
        'time_slots' => $time_slot,
        'status' => 'booked',
        'price' => $item['price'],
        'field_id' => $item['field_id'],
        'user_id' => Auth::id()
      ]);

      Log::info(['data schedule' => $schedule]);

      Transaction::create([
        'user_id' => Auth::id(),
        'schedule_id' => $schedule->id,
        'amount' => $item['price'],
        'status' => 'unpaid',
        'payment_method' => $item['payment_method']
      ]);

      $schedule->update(['status' => 'booked']);
    }

    return back()->with('success', 'Booking berhasil. Silakan lakukan pembayaran.');
  }

  public function confirmPayment() {
    
  }
}
