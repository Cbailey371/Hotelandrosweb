<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\Page;
use App\Models\Room;

class PageController extends Controller
{
    public function edit($slug = 'home')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Data for components
        $rooms = Room::all();
        $carouselImages = \App\Models\Gallery::where('show_in_carousel', true)->get();
        $galleryImages = \App\Models\Gallery::orderBy('order')->get();
        $attractions = \App\Models\Attraction::orderBy('order')->get();

        return view('admin.editor.index', compact('page', 'rooms', 'carouselImages', 'galleryImages', 'attractions'));
    }

    public function preview($slug = 'home')
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        // Data for components
        $rooms = Room::all();
        $carouselImages = \App\Models\Gallery::where('show_in_carousel', true)->get();
        $galleryImages = \App\Models\Gallery::orderBy('order')->get();
        $attractions = \App\Models\Attraction::orderBy('order')->get();

        // Cargar los settings para simular idénticamente la vista pública
        $settings = \App\Models\Setting::pluck('value', 'key')->toArray();

        return view('admin.editor.preview', compact('page', 'rooms', 'carouselImages', 'galleryImages', 'attractions', 'settings'));
    }

    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        $content = $request->input('content');
        \Illuminate\Support\Facades\Log::info('PageController Update: Saving content for slug: ' . $slug);

        // Debug: Check for fontFamily keys in the first section's data
        if (isset($content['sections'][0]['data'])) {
            $data = $content['sections'][0]['data'];
            $keys = array_filter(array_keys($data), fn($k) => str_contains($k, 'fontFamily'));
            \Illuminate\Support\Facades\Log::info('PageController Update: Found fontFamily keys:', $keys);
            \Illuminate\Support\Facades\Log::info('PageController Update: Data snapshot:', array_intersect_key($data, array_flip($keys)));
        }

        $page->update([
            'content' => $content
        ]);

        return response()->json(['success' => true, 'message' => 'Page saved!']);
    }
}
