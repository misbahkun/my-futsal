<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FieldController extends Controller
{
    public function detail($id)
    {
        $field = Field::where('id', $id)->firstOrFail();

        // Initialize arrays
        $status = [];
        $prices = [];

        // Time prices
        $timePrices = [
            'pagi' => 80000,    // 08:00 - 16:00
            'sore' => 150000,    // 16:00 - 00:00
        ];

        // Get all bookings for this field from the schedule table
        $schedules = DB::table('schedule')
            ->where('field_id', $field->id)
            ->get();

        // Populate $status and $prices arrays
        foreach ($schedules as $schedule) {
            // Format the key as "YYYY-MM-DD HH:MM - HH:MM"
            $dateKey = date('Y-m-d', strtotime($schedule->date)) . ' ' . $schedule->time_slots;
            $status[$dateKey] = $schedule->status;
            $prices[$dateKey] = $schedule->price;
        }

        $basePrice = $timePrices['pagi'];
        $fields = Field::where('venue_id', $field->venue_id)->select('id', 'name')->get();

        return view('field-detail', compact('field', 'fields', 'status', 'prices', 'basePrice', 'timePrices'));
    }
}