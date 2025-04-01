<?php

namespace App\Http\Controllers;

use App\Mail\PasswordNotification;
use App\Mail\VerifyEmail;
use App\Models\MyAccount;
use App\Models\Schedule;
use App\Models\Transaction;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{
  public function checkout(Request $request)
  {
    $items = $request->input('items');

    if (empty($items)) {
      return redirect()->route('home')->with('error', 'Your cart is empty. Please add items to your cart.');
    }

    // Decode JSON strings to get item data
    $decodedItems = [];
    $totalPrice = 0;

    foreach ($items as $item) {
      $decodedItem = json_decode($item, true);
      $decodedItems[] = $decodedItem;
      $totalPrice += $decodedItem['price'];
    }

    // Store in session
    session(['cart_items' => $decodedItems]);
    session(['cart_total' => $totalPrice]);

    Log::info(['items stored in session' => $decodedItems, 'total price' => $totalPrice]);

    return redirect()->route('checkout');
  }

  public function index(Request $request)
  {
    try {
      $items = session('cart_items', []);
      $totalPrice = session('cart_total', 0);

      if (empty($items)) {
        return redirect()->route('home')->with('error', 'Your cart is empty. Please add items to your cart.');
      }

      $banks = MyAccount::select('name', 'account_name', 'type', 'image')->get();

      // Explicitly set these values
      // Change this line in the index method
      // In BookingController.php, index method
      $phoneNumber = Auth::check() && Auth::user()->phone_number && strlen(Auth::user()->phone_number) > 5
        ? Auth::user()->phone_number
        : old('phone_number', '');
      $userEmail = Auth::check() ? Auth::user()->email : old('email');

      Log::info($phoneNumber);

      return view('payment', compact('banks', 'items', 'totalPrice', 'phoneNumber', 'userEmail'));
    } catch (Exception $e) {
      Log::error("error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return back()->with('error', 'Terjadi kesalahan saat melakukan checkout: ' . $e->getMessage());
    }
  }

  /**
   * Process booking after user is authenticated
   * User should already be authenticated by verifyAndBook before this method is called
   */
  public function book(Request $request)
  {
    try {
      // Define validation rules
      $rules = [
        'items' => 'required|array|min:1',
        'payment_method' => 'required|string',
      ];

      // Run the validation
      $request->validate($rules, [
        'payment_method.required' => 'Metode pembayaran harus dipilih',
      ]);

      // Ensure user is authenticated
      if (!Auth::check()) {
        return back()->with('error', 'Anda harus login terlebih dahulu.');
      }

      $items = $request->input('items');

      // Extract field IDs, dates, and time slots for checking existing transactions
      $scheduleIdentifiers = [];
      $decodedItems = [];

      foreach ($items as $item) {
        $itemData = json_decode($item, true);
        $decodedItems[] = $itemData;

        // Parse date and time slot
        $dateParts = explode(' ', $itemData['date']);
        $date = $dateParts[0] . ' 00:00:00';
        $timeSlot = substr($itemData['date'], strlen($dateParts[0]) + 1);

        $scheduleIdentifiers[] = [
          'field_id' => $itemData['field_id'],
          'date' => $date,
          'time_slots' => $timeSlot
        ];
      }

      // Check for existing unpaid transaction with same schedule items
      $existingTransaction = $this->findExistingTransaction($scheduleIdentifiers);

      if ($existingTransaction) {
        // Clear cart since we're redirecting to an existing transaction
        session()->forget(['cart_items', 'cart_total']);

        return redirect()->route('payment.pending', ['transactionId' => $existingTransaction->id])
          ->with('info', 'Anda memiliki transaksi yang belum diselesaikan untuk item yang sama.');
      }

      // If no existing transaction found, create a new one
      $transaction = Transaction::create([
        'user_id' => Auth::id(),
        'amount' => 0, // Will update this after processing all items
        'status' => 'unpaid',
        'payment_type' => 'online',
        'payment_method' => $request->input('payment_method')
      ]);

      Log::info(['Created transaction' => $transaction]);

      $totalPrice = 0;

      // Step 2: Create/update all schedules and link them to the transaction
      foreach ($decodedItems as $key => $item) {
        // Parse date and time slot
        $dateParts = explode(' ', $item['date']);
        $date = $dateParts[0] . ' 00:00:00';
        $timeSlot = substr($item['date'], strlen($dateParts[0]) + 1);

        // Create/update schedule with transaction_id
        $schedule = Schedule::updateOrCreate(
          [
            'date' => $date,
            'time_slots' => $timeSlot,
            'field_id' => $item['field_id']
          ],
          [
            'status' => 'booked',
            'price' => $item['price'],
            'user_id' => Auth::id(),
            'transaction_id' => $transaction->id // Link to the transaction
          ]
        );

        Log::info(['Created/updated schedule' => $schedule]);

        $totalPrice += $item['price'];
      }

      // Step 3: Update the transaction with the total price
      $transaction->amount = $totalPrice;
      $transaction->save();

      Log::info(['Updated transaction amount' => $totalPrice]);

      // Clear the cart session after successful booking
      session()->forget(['cart_items', 'cart_total']);

      // Redirect to payment pending page
      return redirect()->route('payment.pending', ['transactionId' => $transaction->id])
        ->with('success', 'Booking berhasil. Silakan lakukan pembayaran.');
    } catch (Exception $e) {
      Log::error("Booking error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return back()->with('error', 'Terjadi kesalahan saat melakukan booking: ' . $e->getMessage());
    }
  }

  /**
   * Find existing unpaid transaction for the same items
   */
  private function findExistingTransaction($scheduleIdentifiers)
  {
    // Look for unpaid transactions that are less than 10 minutes old
    $transactions = Transaction::where('user_id', Auth::id())
      ->where('status', 'unpaid')
      ->where('created_at', '>', now()->subMinutes(10))
      ->get();

    if ($transactions->isEmpty()) {
      return null;
    }

    // For each transaction, check if it has the same schedule items
    foreach ($transactions as $transaction) {
      $schedules = Schedule::where('transaction_id', $transaction->id)->get();

      // Skip if the count doesn't match
      if ($schedules->count() != count($scheduleIdentifiers)) {
        continue;
      }

      // Check if all schedules match
      $allMatch = true;
      foreach ($scheduleIdentifiers as $identifier) {
        $matchFound = false;

        foreach ($schedules as $schedule) {
          if (
            $schedule->field_id == $identifier['field_id'] &&
            $schedule->date == $identifier['date'] &&
            $schedule->time_slots == $identifier['time_slots']
          ) {
            $matchFound = true;
            break;
          }
        }

        if (!$matchFound) {
          $allMatch = false;
          break;
        }
      }

      // If all match, return this transaction
      if ($allMatch) {
        return $transaction;
      }
    }

    return null;
  }

  /**
   * Format phone number for WhatsApp API
   * 
   * @param string $phoneNumber
   * @return string
   */
  private function formatPhoneNumber($phoneNumber)
  {
    // Remove any non-digit characters
    $phoneNumber = preg_replace('/[^0-9]/', '', $phoneNumber);

    // Ensure it has the right format for Fonnte (62XXXXXXXXXX)
    if (substr($phoneNumber, 0, 2) == '62') {
      // Already has 62 country code
      return $phoneNumber;
    } elseif (substr($phoneNumber, 0, 1) == '0') {
      // Starts with 0, replace with 62
      return '62' . substr($phoneNumber, 1);
    } elseif (substr($phoneNumber, 0, 1) == '8') {
      // Starts with 8, add 62
      return '62' . $phoneNumber;
    } else {
      // Any other format, just add 62
      return '62' . $phoneNumber;
    }
  }

  /**
   * Kirim kode verifikasi berdasarkan metode yang dipilih
   */
  public function sendVerification(Request $request)
  {
    try {
      // Logging untuk debugging
      Log::info('Send verification request received', $request->all());

      // Validasi input
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

      // Generate verification code
      $verificationCode = random_int(100000, 999999);

      // Simpan ke session
      session(['verification_code' => $verificationCode]);

      // Log kode yang dibuat (hapus di production)
      Log::info('Verification code generated', [
        'code' => $verificationCode,
        'method' => $request->method
      ]);

      // Proses berdasarkan metode verifikasi
      switch ($request->method) {
        case 'email':
          // Verifikasi email dengan mailable yang sudah ada
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

            // Format phone number
            $to = $this->formatPhoneNumber($request->phone_number);

            // Create message content
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
              throw new \Exception("cURL Error #:" . $err);
            }

            $responseData = json_decode($response, true);

            if (isset($responseData['status']) && $responseData['status'] === true) {
              Log::info("WhatsApp verification code sent to {$to}");
              Log::info("Fonnte API Response: " . $response);

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
          // Dummy response untuk Miscall (untuk implementasi nyata, integrasi dengan penyedia layanan miscall)
          Log::info("Miscall verification would be sent to {$request->phone_number}");
          break;
      }

      // Berhasil
      return response()->json([
        'success' => true,
        'message' => 'Kode verifikasi berhasil dikirim'
      ]);
    } catch (\Exception $e) {
      Log::error("Error in sendVerification: " . $e->getMessage());
      Log::error($e->getTraceAsString());

      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan saat mengirim kode verifikasi: ' . $e->getMessage()
      ], 500);
    }
  }


  public function verifyAndBook(Request $request)
  {
    try {
      // Validate the verification code
      $storedCode = session('verification_code');
      $inputCode = $request->input('verification_code');

      if (!$storedCode || $storedCode != $inputCode) {
        return response()->json([
          'success' => false,
          'message' => 'Kode verifikasi tidak valid'
        ], 422);
      }

      // If user is not authenticated, create and login
      if (!Auth::check()) {
        // Validate required fields
        $request->validate([
          'full_name' => 'required|string|max:255',
          'phone_number' => 'required|string|min:8|max:15',
          'email' => 'required|email|max:255',
        ]);

        // Create user
        $user = $this->createUser($request);

        // Login the user
        Auth::login($user);
      }

      return response()->json([
        'success' => true,
        'message' => 'Verifikasi berhasil',
        'refresh' => !$request->ajax() // Indicate if we need to refresh the page
      ]);
    } catch (Exception $e) {
      Log::error("Verification error: " . $e->getMessage() . "\n" . $e->getTraceAsString());
      return response()->json([
        'success' => false,
        'message' => 'Terjadi kesalahan: ' . $e->getMessage()
      ], 500);
    }
  }

  private function createUser($data)
  {
    // Check if user with email already exists
    $existingUser = User::where('email', $data->email)->first();

    if ($existingUser) {
      return $existingUser;
    }

    // Generate a random password (10 characters)
    $randomPassword = Str::random(10);

    // Create user with the random password
    $user = User::create([
      'full_name' => $data->full_name,
      'email' => $data->email,
      'phone_number' => $data->phone_number,
      'password' => Hash::make($randomPassword),
      'verification_code' => null
    ]);

    // Send email with the password
    try {
      Mail::to($user->email)->send(new PasswordNotification($user, $randomPassword));
      Log::info("Password email sent to {$user->email}");
    } catch (\Exception $e) {
      Log::error("Failed to send password email: " . $e->getMessage());
    }

    return $user;
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