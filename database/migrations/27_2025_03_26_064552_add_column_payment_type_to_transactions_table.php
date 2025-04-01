<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->enum('payment_type', ['online', 'on_site'])->after('amount');
            $table->string('payment_method')->after('payment_type')->change();
            $table->enum('status', ['unpaid', 'pending_verification', 'paid', 'cancelled'])->default('unpaid')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            //
        });
    }
};