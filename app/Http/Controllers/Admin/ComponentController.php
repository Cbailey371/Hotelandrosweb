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
        $index = $request->input('index', null);

        // Fetch common data needed for components
        $rooms = Room::all();
        $carouselImages = \App\Models\Gallery::where('show_in_carousel', true)->get();
        $attractions = \App\Models\Attraction::orderBy('order')->get();
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

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
            'index' => $index,
            'settings' => $settings
        ])->render();

        return response()->json(['html' => $html]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:10240'
        ]);

        if ($request->hasFile('image')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('image'), 'editor_uploads', $request->file('image')->getClientOriginalName());

            return response()->json([
                'success' => true,
                'url' => $path
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No image uploaded'], 400);
    }
}
