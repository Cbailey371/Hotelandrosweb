<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;

class ComponentController extends Controller
{
    public function render(Request $request)
    {
        $type = $request->input('type');
        $data = $request->input('data', []);

        // Fetch common data needed for components (e.g. rooms for the 'rooms' section)
        // ideally we cache or standardize this, but for now we re-fetch
        $rooms = Room::all();
        $carouselImages = \App\Models\Gallery::where('is_carousel', true)->get();
        $attractions = \App\Models\Attraction::where('active', true)->get();

        // Validate component exists (basic security)
        if (!view()->exists("components.sections.{$type}")) {
            return response()->json(['error' => 'Component not found'], 404);
        }

        $html = view("components.sections.{$type}", [
            'data' => $data,
            'mode' => 'editor',
            'rooms' => $rooms,
            'carouselImages' => $carouselImages,
            'attractions' => $attractions,
        ])->render();

        return response()->json(['html' => $html]);
    }
}
