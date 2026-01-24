<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\Gallery;
use App\Models\Attraction;

class ContentController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $gallery = Gallery::orderBy('order')->get();
        $carousel_images = Gallery::where('show_in_carousel', true)->orderBy('carousel_order')->get();
        $attractions = Attraction::orderBy('order')->get();
        return view('admin.content.index', compact('settings', 'gallery', 'carousel_images', 'attractions'));
    }

    public function update(Request $request)
    {
        // Guardar configuraciones de texto
        $data = $request->except(['_token', 'hero_image', 'cafe_image', 'gallery_images']);
        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Procesar imágenes (Hero & Cafe)
        $imageFields = ['hero_image', 'cafe_image'];
        foreach ($imageFields as $field) {
            if ($request->hasFile($field)) {
                $path = \App\Helpers\ImageHelper::storeAsWebp($request->file($field), 'branding');
                Setting::updateOrCreate(['key' => $field], ['value' => $path]);
            } elseif ($request->filled($field)) {
                // Si viene como string (ruta de galería)
                Setting::updateOrCreate(['key' => $field], ['value' => $request->input($field)]);
            }
        }

        return redirect()->back()->with('success', 'Contenido actualizado y optimizado con éxito.');
    }
}
