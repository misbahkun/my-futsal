<?php

namespace App\Http\Controllers;

use App\Mail\PasswordNotification;
use App\Mail\VerifyEmail;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Midtrans\Config;
use Midtrans\Snap;

class BookingController extends Controller
{
  public function checkout(Request $request)
  {
    $items = $request->input('items');

    if (empty($items)) {
      return redirect()->route('home')->with('error', 'Your cart is empty. Please add items to your cart.');
    }

    $decodedItems = [];
    $totalPrice = 0;
    $discountPercentage = 0;
    $totalOriginalPrice = 0;

    $hasDiscount = Auth::check() && Auth::user()->role !== 'non_member';
    $discountPercentage = $hasDiscount ? 10 : 0;

    foreach ($items as $item) {
      $decodedItem = json_decode($item, true);
      Log::info(['DECODED ITEM' => $decodedItem]);

      if ($hasDiscount) {
        $originalPrice = $this->calculateOriginalPrice($decodedItem['price']);
        $decodedItem['originalPrice'] = $originalPrice;
        $totalOriginalPrice += $originalPrice;
      }

      $decodedItems[] = $decodedItem;
      $totalPrice += $decodedItem['price'];
    }

    session(['cart_items' => $decodedItems]);
    session(['cart_total' => $totalPrice]);
    session(['cart_original_total' => $totalOriginalPrice]);
    session(['discount_percentage' => $discountPercentage]);

    return redirect()->route('checkout');
  }

  public function index(Request $request)
  {
    try {
      $items = session('cart_items', []);
      $totalPrice = session('cart_total', 0);
      $originalTotalPrice = session('cart_original_total', 0);
      $discountPercentage = session('discount_percentage', 0);

      if (empty($items)) {
        return redirect()->route('home')->with('error', 'Your cart is empty. Please add items to your cart.');
      }

      $phoneNumber = Auth::check() && Auth::user()->phone_number && strlen(Auth::user()->phone_number) > 5
        ? Auth::user()->phone_number
        : old('phone_number', '');
      $userEmail = Auth::check() ? Auth::user()->email : old('email');

      $hasDiscount = Auth::check() && Auth::user()->role !== 'non_member';
      if ($hasDiscount) {
        $discountPercentage = 10;
      }

      return view('payment', compact(
        'items',
        'totalPrice',
        'originalTotalPrice',
        'discountPercentage',
        'phoneNumber',
        'userEmail'
      ));
    } catch (Exception $e) {
      Log::error("Checkout error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return back()->with('error', 'Terjadi kesalahan saat melakukan checkout: ' . $e->getMessage());
    }
  }

  private function calculateOriginalPrice($discountedPrice)
  {
    return round($discountedPrice / 0.9);
  }

  public function book(Request $request)
  {
    try {
      $request->validate([
        'items' => 'required|array',
        'full_name' => 'required|string|max:255',
        'phone_number' => 'required|string|max:15',
        'email' => 'required|email|max:255',
        'username' => 'nullable|string|max:50|unique:users',
        'verification_code' => 'required_if:is_logged_in,false|string|size:6',
        'verification_method' => 'required_if:is_logged_in,false|in:whatsapp,miscall,email',
      ]);

      $user = Auth::user();
      $isLoggedIn = (bool) Auth::check();

      if (!$user && !$isLoggedIn) {
        $storedCode = session('verification_code');
        $inputCode = $request->input('verification_code');
        if (!$storedCode || $storedCode != $inputCode) {
          return response()->json([
            'success' => false,
            'message' => 'Kode verifikasi tidak valid'
          ], 422);
        }

        $request->validate([
          'full_name' => 'required|string|max:255',
          'phone_number' => 'required|string|min:8|max:15',
          'email' => 'required|email|max:255',
        ]);

        $user = $this->createUser((object) [
          'full_name' => $request->full_name,
          'phone_number' => $this->formatPhoneNumber($request->phone_number),
          'email' => $request->email,
          'username' => $request->username ?? 'guest_' . Str::random(8),
        ], true);
        Auth::login($user);
      } elseif ($user) {
        $user->update([
          'phone_number' => $this->formatPhoneNumber($request->phone_number),
        ]);
      }

      $hasDiscount = $user->role !== 'non_member';
      $discountPercentage = $hasDiscount ? 10 : 0;
      $totalPrice = 0;

      $transaction = Transaction::create([
        'user_id' => $user->id,
        'status' => 'unpaid',
        'amount' => 0,
        'payment_type' => 'online',
        'midtrans_order_id' => 'ORDER-' . time() . '-' . rand(1, 100),
      ]);
      Log::info('Created transaction with midtrans_order_id: ' . $transaction->midtrans_order_id);

      $schedules = [];
      $items = [];
      foreach ($request->items as $item) {
        $decodedItem = json_decode($item, true);
        if ($decodedItem === null) {
          throw new Exception('Invalid item data: Failed to decode JSON');
        }

        $dateTime = $decodedItem['date'];
        $dateParts = explode(' ', $dateTime);
        $date = $dateParts[0];
        $timeSlots = "$dateParts[1] $dateParts[2] $dateParts[3]";

        $price = (int) $decodedItem['price'];
        $originalPrice = $hasDiscount ? $this->calculateOriginalPrice($price) : $price;
        $totalPrice += $price;

        $viewItem = [
          'field_id' => $decodedItem['field_id'],
          'venue' => $decodedItem['venue'],
          'field' => $decodedItem['field'],
          'date' => $dateTime,
          'price' => $price,
        ];
        if ($hasDiscount) {
          $viewItem['originalPrice'] = $originalPrice;
        }
        $items[] = $viewItem;

        $existingSchedule = Schedule::where('field_id', $decodedItem['field_id'])
          ->where('date', $date)
          ->where('time_slots', $timeSlots)
          ->first();
        if ($existingSchedule) {
          throw new Exception('Lapangan sudah dipesan untuk waktu tersebut.');
        }

        $schedule = Schedule::create([
          'field_id' => $decodedItem['field_id'],
          'date' => $date,
          'time_slots' => $timeSlots,
          'status' => 'booked',
          'price' => $price,
          'user_id' => $user->id,
          'transaction_id' => $transaction->id,
        ]);
        $schedules[] = $schedule;
      }

      $transaction->update(['amount' => $totalPrice]);

      Config::$serverKey = config('midtrans.server_key');
      Config::$clientKey = config('midtrans.client_key');
      Config::$isProduction = config('midtrans.is_production');
      Config::$isSanitized = config('midtrans.is_sanitized');
      Config::$is3ds = config('midtrans.is_3ds');

      $itemDetails = [];
      foreach ($schedules as $schedule) {
        $itemDetails[] = [
          'id' => $schedule->field_id,
          'price' => (int) $schedule->price,
          'quantity' => 1,
          'name' => Str::limit($schedule->field->name . ' - ' . $schedule->field->venue->name . ' (' . Carbon::parse($schedule->date)->format('d M Y') . ' ' . $schedule->time_slots . ')', 50, ''),
        ];
      }

      $midtransParams = [
        'transaction_details' => [
          'order_id' => $transaction->midtrans_order_id,
          'gross_amount' => (int) $transaction->amount,
        ],
        'customer_details' => [
          'first_name' => $user->full_name,
          'email' => $user->email,
          'phone' => $user->phone_number,
        ],
        'item_details' => $itemDetails,
        'finish_redirect_url' => route('home'),
        'failure_redirect_url' => route('checkout'),
      ];

      $snapToken = Snap::getSnapToken($midtransParams);

      $originalTotalPrice = session('cart_original_total', 0);

      if ($isLoggedIn) {
        // For logged-in users, return the view with all required variables
        $phoneNumber = $user->phone_number && strlen($user->phone_number) > 5 ? $user->phone_number : $request->phone_number;
        $userEmail = $user->email;
        return view('payment', compact(
          'snapToken',
          'items',
          'totalPrice',
          'hasDiscount',
          'discountPercentage',
          'originalTotalPrice',
          'phoneNumber',
          'userEmail'
        ));
      } else {
        // For non-logged-in users, return JSON for AJAX handling
        return response()->json([
          'success' => true,
          'snapToken' => $snapToken,
          'message' => 'Pemesanan berhasil, silakan lakukan pembayaran.'
        ]);
      }
    } catch (\Exception $e) {
      Log::error("Error in book: " . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat memesan: ' . $e->getMessage()
      ], 400);
    }
  }

  private function formatPhoneNumber($phoneNumber)
  {
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    if (substr($phoneNumber, 0, 2) == '62') {
      return $phoneNumber;
    } elseif (substr($phoneNumber, 0, 1) == '0') {
      return '62' . substr($phoneNumber, 1);
    } elseif (substr($phoneNumber, 0, 1) == '8') {
      return '62' . $phoneNumber;
    } else {
      return '62' . $phoneNumber;
    }
  }

  public function sendVerification(Request $request)
  {
    try {
      $validator = Validator::make($request->all(), [
        'method' => 'required|in:whatsapp,miscall,email',
        'phone_number' => 'required_if:method,whatsapp,miscall',
        'email' => 'required_if:method,email|email'
      ]);

      if ($validator->fails()) {
        Log::error('Validation failed', $validator->errors()->toArray());
        return response()->json([
          'success' => false,
          'message' => 'Validasi gagal: ' . $validator->errors()->first(),
          'errors' => $validator->errors()
        ], 422);
      }

      $verificationCode = random_int(100000, 999999);
      session(['verification_code' => $verificationCode]);

      switch ($request->method) {
        case 'email':
          try {
            Mail::to($request->email)->send(new VerifyEmail($verificationCode));
            Log::info("Email sent to {$request->email}");
          } catch (\Exception $e) {
            Log::error("Failed to send email: " . $e->getMessage());
            return response()->json([
              'success' => false,
              'message' => 'Gagal mengirim email verifikasi'
            ], 500);
          }
          break;

        case 'whatsapp':
          try {
            $apiKey = env('FONNTE_API_KEY');
            $to = $this->formatPhoneNumber($request->phone_number);
            $message = "Kode verifikasi MyFutsal Anda adalah: {$verificationCode}. Kode ini akan kedaluwarsa dalam 5 menit.";

            $curl = curl_init();
            curl_setopt_array($curl, [
              CURLOPT_URL => "https://api.fonnte.com/send",
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => "",
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 30,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => "POST",
              CURLOPT_POSTFIELDS => [
                'target' => $to,
                'message' => $message,
              ],
              CURLOPT_HTTPHEADER => [
                "Authorization: {$apiKey}"
              ],
            ]);

            $response = curl_exec($curl);
            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
              throw new \Exception("cURL Error: " . $err);
            }

            $responseData = json_decode($response, true);
            if (isset($responseData['status']) && $responseData['status'] === true) {
              Log::info("WhatsApp verification sent to {$to}");
              return response()->json([
                'success' => true,
                'message' => 'Kode verifikasi berhasil dikirim melalui WhatsApp'
              ]);
            } else {
              throw new \Exception("Fonnte API Error: " . ($responseData['reason'] ?? 'Unknown error'));
            }
          } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp: " . $e->getMessage());
            return response()->json([
              'success' => false,
              'message' => 'Gagal mengirim kode verifikasi melalui WhatsApp'
            ], 500);
          }
          break;

        case 'miscall':
          Log::info("Miscall verification would be sent to {$request->phone_number}");
          break;
      }

      return response()->json([
        'success' => true,
        'message' => 'Kode verifikasi berhasil dikirim'
      ]);
    } catch (\Exception $e) {
      Log::error("Error in sendVerification: " . $e->getMessage());
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengirim kode verifikasi: ' . $e->getMessage()
      ], 500);
    }
  }

  public function verifyAndBook(Request $request)
  {
    try {
      $storedCode = session('verification_code');
      $inputCode = $request->input('verification_code');

      if (!$storedCode || $storedCode != $inputCode) {
        return response()->json([
          'success' => false,
          'message' => 'Kode verifikasi tidak valid'
        ], 422);
      }

      if (!Auth::check()) {
        $request->validate([
          'full_name' => 'required|string|max:255',
          'phone_number' => 'required|string|min:8|max:15',
          'email' => 'required|email|max:255',
        ]);

        $user = $this->createUser((object) [
          'full_name' => $request->full_name,
          'phone_number' => $this->formatPhoneNumber($request->phone_number),
          'email' => $request->email,
        ], true);
        Auth::login($user);
      }

      return response()->json([
        'success' => true,
        'message' => 'Verifikasi berhasil',
        'refresh' => !$request->ajax()
      ]);
    } catch (\Exception $e) {
      Log::error("Verification error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return response()->with(['error', 'message' => 'Terjadi kesalahan: ' . $e->getMessage()]);
    }
  }

  private function createUser($data, $is_guest = false)
  {
    $existingUser = User::where('email', $data->email)->first();

    if ($existingUser) {
      return $existingUser;
    }

    $randomPassword = Str::random(10);
    $user = User::create([
      'full_name' => $data->full_name,
      'username' => $is_guest ? 'guest_' . Str::random(8) : null,
      'email' => $data->email,
      'phone_number' => $data->phone_number,
      'password' => Hash::make($randomPassword),
      'role' => $is_guest ? 'non_member' : '',
      'is_guest' => $is_guest,
      'verification_code' => null
    ]);

    try {
      Mail::to($user->email)->send(new PasswordNotification($user, $randomPassword));
    } catch (\Exception $e) {
      Log::error("Failed to send password email: " . $e->getMessage());
    }

    return $user;
  }
}
