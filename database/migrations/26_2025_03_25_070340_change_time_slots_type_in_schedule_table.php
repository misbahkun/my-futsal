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
        Schema::table('schedule', function (Blueprint $table) {
            $table->string('time_slots')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule', function (Blueprint $table) {
            $table->enum('time_slots', [
                '23:00 - 00:00',
                '22:00 - 23:00',
                '21:00 - 22:00',
                '20:00 - 21:00',
                '19:00 - 20:00',
                '18:00 - 19:00',
                '17:00 - 18:00',
                '16:00 - 17:00',
                '15:00 - 16:00',
                '14:00 - 15:00',
                '13:00 - 14:00',
                '12:00 - 13:00',
                '11:00 - 12:00',
                '10:00 - 11:00',
                '09:00 - 10:00',
                '08:00 - 09:00'
            ])->change();
        });
    }
};
