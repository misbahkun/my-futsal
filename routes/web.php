<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FieldController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VenueController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/venue', [VenueController::class, 'index'])->name('venue');
Route::get('/v/{slug}', [VenueController::class, 'detail'])->name('venue.detail');

Route::get('/field/{id}', [FieldController::class, 'detail'])->name('field.detail');

Route::group(['prefix' => 'auth'], function () {
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('postRegister');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('postLogin');
    Route::get('/verify-register', [AuthController::class, 'showVerifyRegister'])->name('verifyRegister');
    Route::post('/verify-code', [AuthController::class, 'verifyCode'])->name('verify-code');
});

Route::middleware('auth')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [ProfileController::class, 'showProfile'])->name('profile');
    Route::get('/change-password', [ProfileController::class, 'showChangePasswordForm'])->name('password.form');
    Route::put('/change-password', [ProfileController::class, 'updatePassword'])->name('password.update');

    Route::prefix('dashboard')->group(function () {
        Route::get('/', [DashboardController::class, 'index'])->name('homeDashboard');
        
        // Add search route for transactions
        Route::get('/search-transactions', [DashboardController::class, 'searchTransactions'])->name('dashboard.search-transactions');
        
        Route::get('/users', [UsersController::class, 'index'])->name('admin.users');
        Route::post('/users', [UsersController::class, 'store'])->name('admin.users.store');
        Route::put('/users/{id}', [UsersController::class, 'update'])->name('admin.users.update');
        Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('admin.users.destroy');
        Route::get('/users/{id}', [UsersController::class, 'show'])->name('admin.users.show');
        Route::get('/users/{id}/edit', [UsersController::class, 'getEditData'])->name('admin.users.edit');

        Route::get('/transactions', [TransactionController::class, 'index'])->name('admin.transactions');
        Route::get('/transactions/{id}', [TransactionController::class, 'show'])->name('admin.transactions.show');
        Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('admin.transactions.edit');
        Route::put('/transactions/{id}', [TransactionController::class, 'update'])->name('admin.transactions.update');
        Route::delete('/transactions/{id}', [TransactionController::class, 'destroy'])->name('admin.transactions.destroy');
    });

    Route::post('/book', [BookingController::class, 'book'])->name('book');
    Route::get('/payment/{transactionId}', [BookingController::class, 'showPaymentConfirmation'])->name('paymentDetail');
    Route::post('/upload-payment/{transactionId}', [BookingController::class, 'uploadPayment'])->name('uploadPayment');

    Route::get('/payment/pending/{transactionId}', [PaymentController::class, 'showPaymentPending'])->name('payment.pending');
    Route::get('/payment/check-status/{transactionId}', [PaymentController::class, 'checkPaymentStatus'])->name('check-payment-status');
    Route::get('/payment/change-method/{transactionId}', [PaymentController::class, 'changePaymentMethod'])->name('change-payment-method');
    Route::post('/payment/update-method/{transactionId}', [PaymentController::class, 'updatePaymentMethod'])->name('update-payment-method');
    Route::post('/payment/cancel/{transactionId}', [PaymentController::class, 'cancelTransactionManually'])->name('cancel-transaction');
    Route::post('/payment/{transactionId}/upload-proof', [PaymentController::class, 'uploadPaymentProof'])->name('payment.upload-proof');

    // Invoice
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('user.invoices');
    Route::get('/invoice/{transactionId}', [InvoiceController::class, 'showInvoice'])->name('invoice');
    Route::get('/invoice/{transactionId}/pdf', [InvoiceController::class, 'generateInvoicePdf'])->name('generate.invoice.pdf');
});

// Booking and payment routes
Route::post('/checkout/payment', [BookingController::class, 'checkout'])->name('checkoutPayment');
Route::get('/checkout/payment', [BookingController::class, 'index'])->name('checkout');

Route::post('/check-user-exists', [UsersController::class, 'checkUserExists'])->name('check-user-exists');

// Verification routes for booking
Route::post('/send-verification', [BookingController::class, 'sendVerification'])->name('send-verification');
Route::post('/verify-and-book', [BookingController::class, 'verifyAndBook']);