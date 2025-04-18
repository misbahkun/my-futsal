<?php

namespace App\Http\Controllers;

use App\Models\Sports;
use App\Models\Venue;

class VenueController extends Controller
{
    public function index()
    {
        $query = Venue::query();

        if (request('q')) {
            $query->where('name', 'like', '%' . request('q') . '%');
        }

        if (request('city') && request('city') !== 'Semua Kota') {
            $query->where('city', request('city'));
        }

        if (request('sport') && request('sport') !== 'Semua Olahraga') {
            $query->whereHas('sports', function ($q) {
                $q->where('name', request('sport'));
            });
        }

        $venues = $query->latest()->paginate(6)->withQueryString(); // withQueryString supaya pagination bawa filter-nya

        $cities = Venue::select('city')->distinct()->get();
        $sports = Sports::all();

        return view('venue', compact('venues', 'cities', 'sports'));
    }


    public function detail($slug)
    {
        $venue = Venue::with(['fields.schedules', 'sports', 'galleries'])->where('slug', $slug)->firstOrFail();

        return view('detail', compact('venue'));
    }
}