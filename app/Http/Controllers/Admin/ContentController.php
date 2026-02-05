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
        // 1. Definir campos permitidos (Whitelist) para evitar Mass Assignment en settings
        $allowedKeys = [
            'hero_title',
            'hero_subtitle',
            'hero_overlay_color',
            'hero_overlay_opacity',
            'hero_bg_opacity',
            'hero_image', // Permitir path de galería
            'cafe_title',
            'cafe_description',
            'cafe_image', // Permitir path de galería
            'room_section_title',
            'room_section_description',
            'contact_email',
            'contact_phone',
            'hotel_address',
            'hotel_email'
        ];

        // 2. Validación de archivos (Tipos MIME y Tamaño)
        $request->validate([
            'hero_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'cafe_image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // 3. Procesar solo los datos dentro de la lista blanca
        $data = $request->only($allowedKeys);

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // 4. Procesar imágenes de forma segura
        if ($request->hasFile('hero_image_file')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('hero_image_file'), 'branding');
            Setting::updateOrCreate(['key' => 'hero_image'], ['value' => $path]);
        }

        if ($request->hasFile('cafe_image_file')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('cafe_image_file'), 'branding');
            Setting::updateOrCreate(['key' => 'cafe_image'], ['value' => $path]);
        }

        return redirect()->back()->with('success', 'Contenido actualizado correctamente y verificado.');
    }
}
