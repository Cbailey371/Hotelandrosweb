<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Gallery;

class GalleryController extends Controller
{
    public function index()
    {
        $images = Gallery::orderBy('order')->get();
        return view('admin.gallery.index', compact('images'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'gallery_images.*' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $image) {
                $path = \App\Helpers\ImageHelper::storeAsWebp($image, 'gallery');

                Gallery::create([
                    'image_path' => $path,
                    'title_es' => $image->getClientOriginalName(),
                    'title_en' => $image->getClientOriginalName(),
                    'order' => Gallery::max('order') + 1,
                    'show_in_carousel' => $request->has('show_in_carousel') ? 1 : 0
                ]);
            }
        }

        return redirect()->back()->with('success', 'Imágenes subidas y optimizadas con éxito.');
    }

    public function destroy(string $id)
    {
        $image = Gallery::findOrFail($id);

        $path = str_replace('/storage/', '', $image->image_path);
        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($path);
        }

        $image->delete();

        return redirect()->back()->with('success', 'Imagen eliminada con éxito.');
    }

    public function toggleCarousel(Gallery $gallery)
    {
        $gallery->update([
            'show_in_carousel' => !$gallery->show_in_carousel,
            'carousel_order' => $gallery->show_in_carousel ? 0 : (Gallery::where('show_in_carousel', true)->max('carousel_order') + 1)
        ]);

        return redirect()->back()->with('success', 'Visibilidad en carrusel actualizada.');
    }
}
