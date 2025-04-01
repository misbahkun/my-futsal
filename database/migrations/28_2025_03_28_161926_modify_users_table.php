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
        Schema::table('users', function (Blueprint $table) {
            $table->string('full_name')->nullable()->after('id')->change();
            $table->string('email')->nullable()->after('full_name')->change();
            $table->timestamp('email_verified_at')->nullable()->after('address')->change();
            $table->string('phone_number')->nullable()->after('role')->change();
            $table->string('verification_code')->nullable()->after('phone_number')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};