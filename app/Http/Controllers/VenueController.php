<?php

namespace App\Http\Controllers;

use App\Models\Venue;

class VenueController extends Controller
{
    public function index() {
        $venues = Venue::latest()->paginate(6);

        return view('venue', compact('venues'));
    }

    public function detail($slug)
    {
        $venue = Venue::with(['fields.schedules', 'sports', 'galleries'])->where('slug', $slug)->firstOrFail();

        return view('detail', compact('venue'));
    }
}