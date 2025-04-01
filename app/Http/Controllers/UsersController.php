<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    /**
     * Check if a user already exists with the given email or phone number
     * based on the verification method selected
     */
    public function checkUserExists(Request $request)
    {
        $request->validate([
            'email' => 'required_if:method,email|email',
            'phone_number' => 'required_if:method,whatsapp,miscall',
            'method' => 'required|in:email,whatsapp,miscall'
        ]);

        $method = $request->method;
        $exists = false;
        $message = '';

        // Only check what's relevant based on the verification method
        if ($method === 'email') {
            $exists = User::where('email', $request->email)->exists();
            $message = $exists ? 'User with this email already exists.' : 'Email is available.';
        } else {
            // For whatsapp or miscall methods, check phone number
            $exists = User::where('phone_number', $request->phone_number)->exists();
            $message = $exists ? 'User with this phone number already exists.' : 'Phone number is available.';
        }

        return response()->json([
            'exists' => $exists,
            'message' => $message
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('id', 'full_name', 'role', 'email', 'phone_number', 'email_verified_at', 'updated_at')
            ->where('role', '!=', 'admin')
            ->get()
            ->map(function ($user) {
                if ($user->email_verified_at) {
                    $emailVerifiedAt = Carbon::parse($user->email_verified_at);
                    $user->email_verified_at = $emailVerifiedAt->setTimezone('Asia/Jakarta');
                }

                if ($user->updated_at) {
                    $user->updated_at = $user->updated_at->setTimezone('Asia/Jakarta');
                }

                return $user;
            });
        return view('dashboard.users', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users',
                'email' => 'required|string|email|max:255|unique:users',
                'phone_number' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8',
                'address' => 'nullable|string|max:255',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Create new user
            $user = new User();
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password = Hash::make($request->password);
            $user->address = $request->address;
            $user->role = 'non_member'; // Default role
            $user->image = 'user.png'; // Default image

            // If admin verifies directly
            if ($request->has('verified') && $request->verified) {
                $user->email_verified_at = now();
            }

            $user->save();

            return redirect()->route('admin.users')
                ->with('success', 'User created successfully');
        } catch (\Exception $e) {
            Log::error('Failed to create user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to create user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        try {
            $user = User::findOrFail($id);
            return view('dashboard.users.edit', compact('user'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user for editing: ' . $e->getMessage());
            return redirect()->route('admin.users')
                ->with('error', 'User not found');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'full_name' => 'required|string|max:255',
                'username' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user->id)
                ],
                'email' => [
                    'required',
                    'string',
                    'email',
                    'max:255',
                    Rule::unique('users')->ignore($user->id)
                ],
                'phone_number' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->ignore($user->id)
                ],
                'password' => 'nullable|string|min:8',
                'address' => 'nullable|string|max:255',
                'role' => 'nullable|in:admin,member,non_member',
            ]);

            if ($validator->fails()) {
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            // Update user
            $user->full_name = $request->full_name;
            $user->username = $request->username;
            $user->email = $request->email;
            $user->phone_number = $request->phone_number;
            $user->address = $request->address;

            // Update role if provided and allowed
            if ($request->filled('role')) {
                $user->role = $request->role;
            }

            // Update password if provided
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // Update verification status if changing
            if ($request->has('verified') && $request->verified && !$user->email_verified_at) {
                $user->email_verified_at = now();
            } else if ($request->has('verified') && !$request->verified && $user->email_verified_at) {
                $user->email_verified_at = null;
            }

            $user->save();

            return redirect()->route('admin.users')
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            Log::error('Failed to update user: ' . $e->getMessage());
            return redirect()->back()
                ->with('error', 'Failed to update user: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $user = User::findOrFail($id);
            $userName = $user->full_name;

            // Delete user
            $user->delete();

            return redirect()->route('admin.users')
                ->with('success', "User '{$userName}' has been deleted successfully");
        } catch (\Exception $e) {
            Log::error('Failed to delete user: ' . $e->getMessage());
            return redirect()->route('admin.users')
                ->with('error', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $user = User::findOrFail($id);

            // For AJAX requests, return JSON
            if (request()->ajax()) {
                return response()->json([
                    'id' => $user->id,
                    'full_name' => $user->full_name,
                    'username' => $user->username,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'address' => $user->address,
                    'role' => $user->role,
                    'email_verified_at' => $user->email_verified_at,0-
                    'created_at' => $user->created_at ? $user->created_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : null,
                    'updated_at' => $user->updated_at ? $user->updated_at->setTimezone('Asia/Jakarta')->format('d M Y, H:i') : null,
                ]);
            }

            // For regular requests, return view
            return view('dashboard.users.show', compact('user'));
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user: ' . $e->getMessage());

            if (request()->ajax()) {
                return response()->json(['error' => 'User not found'], 404);
            }

            return redirect()->route('admin.users')
                ->with('error', 'User not found');
        }
    }

    /**
     * Return user data for editing via AJAX
     */
    public function getEditData(string $id)
    {
        try {
            $user = User::findOrFail($id);

            return response()->json([
                'id' => $user->id,
                'full_name' => $user->full_name,
                'username' => $user->username,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $user->address,
                'role' => $user->role,
                'email_verified_at' => $user->email_verified_at,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to retrieve user data for editing: ' . $e->getMessage());
            return response()->json(['error' => 'User not found'], 404);
        }
    }
}