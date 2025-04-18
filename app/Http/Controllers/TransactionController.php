<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\MyAccount;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    /**
     * Display a listing of the transactions.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $banks = MyAccount::select('name')->get();

        $transactions = Transaction::with('user')->latest()->paginate(10);
        $users = User::all();
        return view('dashboard.transactions.index', compact('transactions', 'users', 'banks'));
    }

    /**
     * Display the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::with(['user', 'schedule'])->findOrFail($id);
        return view('dashboard.transactions.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified transaction.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::findOrFail($id);
        $users = User::all();
        $banks = MyAccount::select('name')->get();
        return view('dashboard.transactions.edit', compact('transaction', 'users', 'banks'));
    }

    /**
     * Update the specified transaction in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        // Validate request
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'amount' => 'required|numeric|min:0',
            'payment_type' => 'required|in:online,on_site',
            'payment_method' => 'required|string',
            'status' => 'required|in:unpaid,pending_verification,paid,cancelled',
            'payment_proof' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
        ]);

        // Update transaction
        $transaction = Transaction::findOrFail($id);

        // Handle payment proof upload
        if ($request->hasFile('payment_proof')) {
            // Delete old file if it exists
            if ($transaction->payment_proof) {
                Storage::disk('public')->delete($transaction->payment_proof);
            }

            $file = $request->file('payment_proof');
            $paymentProofPath = $file->store('payment_proofs', 'public');

            $transaction->payment_proof = $paymentProofPath;
        }

        $transaction->user_id = $request->user_id;
        $transaction->amount = $request->amount;
        $transaction->payment_type = $request->payment_type;
        $transaction->payment_method = $request->payment_method;
        $transaction->status = $request->status;
        $transaction->save();

        return redirect()->route('admin.transactions')->with('success', 'Transaction updated successfully.');
    }

    /**
     * Remove the specified transaction from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // Check if user is admin
        if (Auth::user()->role !== 'admin') {
            return redirect()->route('homeDashboard')->with('error', 'You do not have permission to access this page.');
        }

        $transaction = Transaction::findOrFail($id);

        // Delete payment proof file if it exists
        if ($transaction->payment_proof) {
            Storage::disk('public')->delete($transaction->payment_proof);
        }

        $transaction->delete();

        return redirect()->route('admin.transactions')->with('success', 'Transaction deleted successfully.');
    }
}