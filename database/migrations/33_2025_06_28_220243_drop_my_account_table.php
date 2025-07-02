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
        Schema::dropIfExists('my_account');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('my_account', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }
};
