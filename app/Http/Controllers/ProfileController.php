<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{

    public function showProfile()
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('homeDashboard')->with('error', 'User not found');
        }
        return view('dashboard.profile', compact('user'));
    }

    /**
     * Show the change password form.
     *
     * @return \Illuminate\View\View
     */
    public function showChangePasswordForm()
    {
        return view('dashboard.users.change-password');
    }

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        // Validasi dasar dulu
        $baseValidator = Validator::make($request->all(), [
            'current_password' => ['required', 'string', function ($attribute, $value, $fail) {
                if (!Hash::check($value, Auth::user()->password)) {
                    $fail('Password saat ini tidak benar.');
                }
            }],
            'password' => [
                'required',
                'string',
                'confirmed',
                'min:8',
                'different:current_password'
            ],
        ], [
            'password.different' => 'Password baru harus berbeda dari password saat ini.',
            'password.min' => 'Password minimal harus 8 karakter.',
        ]);

        if ($baseValidator->fails()) {
            return redirect()->back()
                ->withErrors($baseValidator)
                ->withInput();
        }

        // Periksa setiap komponen password secara terpisah
        $password = $request->input('password');
        $errors = [];

        if (!preg_match('/[a-z]/', $password)) {
            $errors['password'] = 'Password harus mengandung setidaknya satu huruf kecil.';
        } elseif (!preg_match('/[A-Z]/', $password)) {
            $errors['password'] = 'Password harus mengandung setidaknya satu huruf besar.';
        } elseif (!preg_match('/[0-9]/', $password)) {
            $errors['password'] = 'Password harus mengandung setidaknya satu angka.';
        } elseif (!preg_match('/[@$!%*#?&]/', $password)) {
            $errors['password'] = 'Password harus mengandung setidaknya satu simbol khusus (@$!%*#?&).';
        }

        if (!empty($errors)) {
            return redirect()->back()
                ->withErrors($errors)
                ->withInput();
        }

        try {
            // Get the authenticated user
            $user = Auth::user();

            // Update the password
            $user->password = Hash::make($request->password);
            $user->save();

            return redirect()->back()->with('success', 'Password berhasil diubah!');
        } catch (\Exception $e) {
            Log::error('Password change error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengubah password. Silakan coba lagi nanti.');
        }
    }
}