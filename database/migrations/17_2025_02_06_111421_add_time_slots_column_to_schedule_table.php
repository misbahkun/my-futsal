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
            $table->enum('time_slots',
                ['22:00 - 00:00', 
                '20:00 - 22:00',
                '18:00 - 20:00',
                '16:00 - 18:00',
                '14:00 - 16:00',
                '12:00 - 14:00',
                '10:00 - 12:00',
                '08:00 - 10:00',])->after('date');

                $table->unique(['field_id', 'date', 'time_slots']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('schedule', function (Blueprint $table) {
            $table->dropUnique(['field_id', 'date', 'time_slots']);
            $table->dropColumn('time_slots');
        });
    }
};
