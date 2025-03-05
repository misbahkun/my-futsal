<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class UsersController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::select('full_name', 'role', 'email', 'phone_number', 'email_verified_at', 'updated_at')
            ->where('role', '!=', 'admin')
            ->get()
            ->map(function ($user) {
                if ($user->email_verified_at) {
                    $emailFerifiedAt = Carbon::parse($user->email_verified_at);
                    $user->email_verified_at = $emailFerifiedAt->setTimezone('Asia/Jakarta');
                }

                if ($user->updated_at) {
                    $user->updated_at = $user->updated_at->setTimezone('Asia/Jakarta');
                }
                
                return $user;
            });
        return view('dashboard.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
