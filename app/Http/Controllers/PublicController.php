<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Setting;
use App\Models\Gallery;
use App\Models\Attraction;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $page = \App\Models\Page::where('slug', 'home')->first();

        // If no page found (or not published), fallback to old welcome or 404
        if (!$page) {
            // Fallback logic could go here, for now let's assume seed ran.
            // If completely missing, maybe show basic welcome?
            // return view('welcome');
        }

        // Data needed for components
        $rooms = Room::where('status', 'active')->with(['galleries'])->get();
        // $settings still needed for layout meta tags? Yes, layout uses $settings.
        $settings = Setting::pluck('value', 'key')->all();
        $gallery = Gallery::orderBy('order')->get();
        $attractions = Attraction::orderBy('order')->get();
        $carouselImages = Gallery::where('show_in_carousel', true)->orderBy('carousel_order')->get();

        return view('page', compact('page', 'rooms', 'settings', 'gallery', 'attractions', 'carouselImages'));
    }
}
