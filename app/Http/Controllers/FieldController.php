<?php

namespace App\Http\Controllers;

use App\Models\Field;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FieldController extends Controller
{
    public function detail($id)
    {
        $field = Field::with(['venue', 'schedules'])
            ->where('id', $id)
            ->firstOrFail();

        $status = [];
        $prices = [];
        $minPrice = $field->schedules->min('price');
    
        foreach ($field->schedules as $schedule) {
            $dateTime = Carbon::parse($schedule->date)->format('Y-m-d') . ' ' . $schedule->time_slots;
            $status[$dateTime] = $schedule->status;        
            $prices[$dateTime] = $schedule->price;
        }

    
        $fields = Field::where('venue_id', $field->venue_id)->select('id', 'name')->get();
    
        return view('field-detail', compact('field', 'fields', 'status', 'prices', 'minPrice'));
    }
}
