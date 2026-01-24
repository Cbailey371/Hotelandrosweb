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
        $rooms = Room::where('status', 'active')->with(['galleries'])->get();
        $settings = Setting::pluck('value', 'key')->all();
        $gallery = Gallery::orderBy('order')->get();
        $attractions = Attraction::orderBy('order')->get();
        $carouselImages = Gallery::where('show_in_carousel', true)->orderBy('carousel_order')->get();

        return view('welcome', compact('rooms', 'settings', 'gallery', 'attractions', 'carouselImages'));
    }
}
