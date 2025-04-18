<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FieldController extends Controller
{
    public function detail($id)
    {
        $field = Field::where('id', $id)->firstOrFail();

        // Initialize arrays
        $status = [];
        $prices = [];
        $originalPrices = [];

        // Time prices
        $timePrices = [
            'pagi' => 80000,    // 08:00 - 16:00
            'sore' => 150000,    // 16:00 - 00:00
        ];

        // Calculate discount if user is not non_member
        $discount = 0;
        $hasDiscount = false;
        if (Auth::check() && Auth::user()->role !== 'non_member') {
            $discount = 0.1; // 10% discount
            $hasDiscount = true;

            // Prepare original and discounted prices
            $originalTimePrices = [
                'pagi' => 80000,
                'sore' => 150000
            ];
            $discountedTimePrices = [
                'pagi' => round(80000 * (1 - $discount)),
                'sore' => round(150000 * (1 - $discount))
            ];

            $timePrices = $discountedTimePrices;
        }

        // Get all bookings for this field from the schedule table
        $schedules = DB::table('schedule')
            ->where('field_id', $field->id)
            ->get();

        // Populate $status and $prices arrays
        foreach ($schedules as $schedule) {
            // Format the key as "YYYY-MM-DD HH:MM - HH:MM"
            $dateKey = date('Y-m-d', strtotime($schedule->date)) . ' ' . $schedule->time_slots;
            $status[$dateKey] = $schedule->status;
        }

        $basePrice = $timePrices['pagi'];
        $fields = Field::where('venue_id', $field->venue_id)->select('id', 'name')->get();

        // If discount is active, prepare original prices
        if ($hasDiscount) {
            $originalPrices = [];
            $timeSlots = [
                '08:00 - 09:00',
                '09:00 - 10:00',
                '10:00 - 11:00',
                '11:00 - 12:00',
                '12:00 - 13:00',
                '13:00 - 14:00',
                '14:00 - 15:00',
                '15:00 - 16:00',
                '16:00 - 17:00',
                '17:00 - 18:00',
                '18:00 - 19:00',
                '19:00 - 20:00',
                '20:00 - 21:00',
                '21:00 - 22:00',
                '22:00 - 23:00',
                '23:00 - 00:00'
            ];

            foreach ($timeSlots as $timeSlot) {
                $startHour = intval(explode(':', $timeSlot)[0]);
                $originalPrice = ($startHour >= 8 && $startHour < 16) ? 80000 : 150000;

                // Add entries for next 7 days
                $baseDate = now();
                for ($i = 0; $i < 7; $i++) {
                    $date = $baseDate->addDays($i)->format('Y-m-d');
                    $fullDateKey = $date . ' ' . $timeSlot;
                    $originalPrices[$fullDateKey] = $originalPrice;
                }
            }
        }

        return view('field-detail', compact(
            'field',
            'fields',
            'status',
            'prices',
            'basePrice',
            'timePrices',
            'hasDiscount',
            'discount',
            'originalPrices'
        ));
    }
}