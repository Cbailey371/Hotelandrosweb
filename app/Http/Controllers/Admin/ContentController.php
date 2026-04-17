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
            // Hero
            'hero_title_es',
            'hero_title_en',
            'hero_subtitle_es',
            'hero_subtitle_en',
            'hero_overlay_enabled',
            'hero_overlay_color',
            'hero_overlay_opacity',
            'hero_bg_opacity',
            'hero_image',
            'hero_gap',

            // Rooms
            'rooms_badge_es',
            'rooms_badge_en',
            'rooms_title_es',
            'rooms_title_en',
            'rooms_description_es',
            'rooms_description_en',

            // Cafe
            'cafe_title_es',
            'cafe_title_en',
            'cafe_description_es',
            'cafe_description_en',
            'cafe_image',
            'cafe_overlay_color',
            'cafe_overlay_opacity',
            'cafe_text_color',
            'cafe_image_badge_es',
            'cafe_image_badge_en',
            'cafe_image_title_es',
            'cafe_image_title_en',
            'cafe_feature1_icon',
            'cafe_feature1_title_es',
            'cafe_feature1_title_en',
            'cafe_feature1_desc_es',
            'cafe_feature1_desc_en',
            'cafe_feature2_icon',
            'cafe_feature2_title_es',
            'cafe_feature2_title_en',
            'cafe_feature2_desc_es',
            'cafe_feature2_desc_en',

            // Location
            'location_badge_es',
            'location_badge_en',
            'location_title_es',
            'location_title_en',
            'location_description_es',
            'location_description_en',
            'google_maps_iframe',

            // Attractions & Carousel Titles
            'attractions_badge_es',
            'attractions_badge_en',
            'attractions_title_es',
            'attractions_title_en',
            'carousel_badge_es',
            'carousel_badge_en',
            'carousel_title_es',
            'carousel_title_en',

            // Footer
            'footer_description_es',
            'footer_description_en',
            'footer_contact_description_es',
            'footer_contact_description_en',
            'footer_socials_json',
            'footer_copyright_es',
            'footer_copyright_en',
            'footer_policies_es',
            'footer_policies_en',

            // General Contact
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
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('hero_image_file'), 'branding', 'hotel-hero-bg');
            Setting::updateOrCreate(['key' => 'hero_image'], ['value' => $path]);
        }

        if ($request->hasFile('cafe_image_file')) {
            $path = \App\Helpers\ImageHelper::storeAsWebp($request->file('cafe_image_file'), 'branding', 'hotel-cafe-bg');
            Setting::updateOrCreate(['key' => 'cafe_image'], ['value' => $path]);
        }

        \Illuminate\Support\Facades\Cache::forget('home_page_data');

        return redirect()->back()
            ->with('success', 'Contenido actualizado correctamente y verificado.')
            ->with('active_section', $request->input('active_section'));
    }
}
