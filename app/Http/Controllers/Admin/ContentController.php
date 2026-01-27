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
        // Excluimos explícitamente los campos de archivo y el token para no guardarlos como basura en la tabla settings
        $data = $request->except(['_token', '_method', 'hero_image', 'cafe_image', 'gallery_images', 'hero_image_file', 'cafe_image_file', 'image_file']);

        foreach ($data as $key => $value) {
            // Solo guardamos si no es nulo (o si queremos vaciarlo explícitamente)
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }

        // Procesar imágenes (Hero & Cafe)
        $imageFields = [
            'hero_image' => 'hero_image_file',
            'cafe_image' => 'cafe_image_file'
        ];

        foreach ($imageFields as $settingKey => $fileKey) {
            if ($request->hasFile($fileKey)) {
                $path = \App\Helpers\ImageHelper::storeAsWebp($request->file($fileKey), 'branding');
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $path]);
            } elseif ($request->filled($settingKey)) {
                // Si viene como string (ruta elegida de la librería)
                Setting::updateOrCreate(['key' => $settingKey], ['value' => $request->input($settingKey)]);
            }
        }

        return redirect()->back()->with('success', 'Contenido actualizado correctamente.');
    }
}
