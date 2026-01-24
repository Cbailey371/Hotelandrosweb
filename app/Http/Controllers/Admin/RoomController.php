<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Gallery;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return view('admin.rooms.index', compact('rooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $galleryImages = Gallery::all();
        return view('admin.rooms.create', compact('galleryImages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name_es' => 'required',
            'name_en' => 'required',
            'description_es' => 'nullable',
            'description_en' => 'nullable',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'status' => 'required',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|max:5120',
            'main_image_id' => 'nullable|exists:galleries,id',
            'amenities' => 'nullable|string',
            'gallery_ids' => 'nullable|array',
            'gallery_ids.*' => 'exists:galleries,id',
            'new_gallery_images' => 'nullable|array',
            'new_gallery_images.*' => 'image|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = \App\Helpers\ImageHelper::storeAsWebp($request->file('image_file'), 'rooms');
        } elseif ($request->filled('main_image_id')) {
            $gallery = Gallery::find($request->main_image_id);
            $validated['image'] = $gallery->image_path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        if ($request->filled('amenities')) {
            $validated['amenities'] = array_map('trim', explode(',', $request->amenities));
        } else {
            $validated['amenities'] = [];
        }

        $room = Room::create($validated);

        // Sincronizar imágenes existentes seleccionadas
        if ($request->filled('gallery_ids')) {
            $room->galleries()->sync($request->gallery_ids);
        }

        // Procesar nuevas imágenes subidas para la galería
        if ($request->hasFile('new_gallery_images')) {
            foreach ($request->file('new_gallery_images') as $image) {
                $path = \App\Helpers\ImageHelper::storeAsWebp($image, 'gallery');
                $gallery = Gallery::create([
                    'image_path' => $path,
                    'title_es' => $room->name_es, // Título por defecto
                    'title_en' => $room->name_en,
                ]);
                $room->galleries()->attach($gallery->id);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Habitación creada con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $room = Room::findOrFail($id);
        $galleryImages = Gallery::all();
        return view('admin.rooms.edit', compact('room', 'galleryImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::findOrFail($id);

        $validated = $request->validate([
            'name_es' => 'required',
            'name_en' => 'required',
            'description_es' => 'nullable',
            'description_en' => 'nullable',
            'price' => 'required|numeric',
            'capacity' => 'required|integer',
            'status' => 'required',
            'image_url' => 'nullable|url',
            'image_file' => 'nullable|image|max:5120',
            'main_image_id' => 'nullable|exists:galleries,id',
            'amenities' => 'nullable|string',
            'gallery_ids' => 'nullable|array',
            'gallery_ids.*' => 'exists:galleries,id',
            'new_gallery_images' => 'nullable|array',
            'new_gallery_images.*' => 'image|max:5120',
        ]);

        if ($request->hasFile('image_file')) {
            $validated['image'] = \App\Helpers\ImageHelper::storeAsWebp($request->file('image_file'), 'rooms');
        } elseif ($request->filled('main_image_id')) {
            $gallery = Gallery::find($request->main_image_id);
            $validated['image'] = $gallery->image_path;
        } elseif ($request->filled('image_url')) {
            $validated['image'] = $request->image_url;
        }

        if ($request->filled('amenities')) {
            $validated['amenities'] = array_map('trim', explode(',', $request->amenities));
        } else {
            $validated['amenities'] = [];
        }

        $room->update($validated);

        // Sincronizar imágenes existentes seleccionadas
        if ($request->has('gallery_ids')) {
            $room->galleries()->sync($request->gallery_ids);
        } else {
            // Si el campo existe pero está vacío (se desmarcaron todas), desvincular todo.
            // Pero hay que tener cuidado si el input no se envía.
            // Los checkboxes no enviados suelen ser null. 
            // Mejor estrategia: sync([]) si no hay input, pero solo si estamos seguros que es una actualización del form.
            $room->galleries()->sync($request->input('gallery_ids', []));
        }

        // Procesar nuevas imágenes subidas para la galería
        if ($request->hasFile('new_gallery_images')) {
            foreach ($request->file('new_gallery_images') as $image) {
                $path = \App\Helpers\ImageHelper::storeAsWebp($image, 'gallery');
                $gallery = Gallery::create([
                    'image_path' => $path,
                    'title_es' => $room->name_es,
                    'title_en' => $room->name_en,
                ]);
                $room->galleries()->attach($gallery->id);
            }
        }

        return redirect()->route('admin.rooms.index')->with('success', 'Habitación actualizada con éxito.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::findOrFail($id);
        $room->delete();

        return redirect()->route('admin.rooms.index')->with('success', 'Habitación eliminada con éxito.');
    }
}
