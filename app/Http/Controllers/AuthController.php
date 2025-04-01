<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function showRegister()
    {
        return view('register');
    }

    public function showLogin()
    {
        return view('login');
    }

    public function showVerifyRegister()
    {
        return view('verify-register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::query()->where('email', $request->email)->first();

        if ($user && $user->hasVerifiedEmail()) {
            return redirect()->back()->withErrors(['email' => 'Email already registered.']);
        }

        $verificationCode = random_int(100000, 999999);

        if ($user) {
            $user->verification_code = $verificationCode;
            $user->save();
        } else {
            $user = User::query()->create([
                'email' => $request->email,
                'verification_code' => $verificationCode,
            ]);
        }

        Mail::to($request->email)->send(new VerifyEmail($verificationCode));

        $request->session()->put('email', $request->email);

        return redirect()->route('verifyRegister')->with('success', 'Verification email sent!');
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'verification_code' => 'required|numeric',
            'username' => 'required|string|max:50',
            'full_name' => 'required|string|max:50',
            'password' => 'required|string|max:50',
            'phone_number' => 'required|string|max:15',
        ]);

        if (User::query()->where('username', $request->username)->first()) {
            return redirect()->back()->withErrors(['username' => 'Username already taken.']);
        }

        $email = $request->session()->get('email');

        $user = User::query()->where('email', $email)
            ->where('verification_code', $request->verification_code)
            ->first();

        if ($user) {
            $user->username = $request->username;
            $user->full_name = $request->full_name;
            $user->password = Hash::make($request->password);
            $user->phone_number = $request->phone_number;
            $user->verification_code = null;
            $user->markEmailAsVerified();
            $user->save();

            return redirect()->route('login')->with('success', 'Email verified successfully!');
        } else {
            return redirect()->route('verifyRegister')->withErrors(['verification_code' => 'Invalid verification code.']);
        }
    }

    public function login(Request $request)
    {
        // Validate the input, allowing either username or email
        $request->validate([
            'login' => 'required|string', // Generic login field
            'password' => 'required|string',
        ]);

        // Determine if the input is an email or username
        $loginType = filter_var($request->input('login'), FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        // Prepare credentials array
        $credentials = [
            $loginType => $request->input('login'),
            'password' => $request->input('password')
        ];

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            // Check if there's a pending booking
            if (session()->has('pending_booking')) {
                $pendingBooking = session('pending_booking');

                // Process and store the pending items in cart session
                if (isset($pendingBooking['items'])) {
                    $decodedItems = [];
                    $totalPrice = 0;

                    foreach ($pendingBooking['items'] as $item) {
                        $decodedItem = json_decode($item, true);
                        $decodedItems[] = $decodedItem;
                        $totalPrice += $decodedItem['price'];
                    }

                    // Store in the cart session
                    session(['cart_items' => $decodedItems]);
                    session(['cart_total' => $totalPrice]);
                }

                session()->forget('pending_booking');

                // Redirect to checkout to complete the booking
                return redirect()->route('checkout')->with('info', 'Silakan lanjutkan pemesanan Anda.');
            }

            return redirect()->intended('dashboard')->with('success', 'Selamat Datang ');
        }

        return back()->withErrors([
            'login' => 'Username/Email atau Password salah',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('home')->with('success', 'Berhasil Logout!');
    }
}