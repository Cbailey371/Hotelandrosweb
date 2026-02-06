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
        $carouselImages = \App\Models\Gallery::where('is_carousel', true)->get();
        $attractions = \App\Models\Attraction::where('active', true)->get();

        return view('admin.editor.index', compact('page', 'rooms', 'carouselImages', 'attractions'));
    }

    public function update(Request $request, $slug)
    {
        $page = Page::where('slug', $slug)->firstOrFail();
        $page->update([
            'content' => $request->input('content')
        ]);

        return response()->json(['success' => true, 'message' => 'Page saved!']);
    }
}
