<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attraction;
use Illuminate\Http\Request;
use App\Helpers\ImageHelper;

class AttractionController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'title_es' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'description_es' => 'required|string',
            'description_en' => 'required|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_path' => 'nullable|string',
        ]);

        $data = $request->except(['image_file']);

        if ($request->hasFile('image_file')) {
            $data['image_path'] = ImageHelper::storeAsWebp($request->file('image_file'), 'attractions', $request->title_es);
        }

        $attraction = Attraction::create($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Atractivo turístico añadido con éxito.',
                'attraction' => $attraction
            ]);
        }

        return redirect()->back()->with('success', 'Atractivo turístico añadido con éxito.');
    }

    public function update(Request $request, Attraction $attraction)
    {
        $request->validate([
            'title_es' => 'sometimes|string|max:255',
            'title_en' => 'sometimes|string|max:255',
            'description_es' => 'sometimes|string',
            'description_en' => 'sometimes|string',
            'image_file' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'image_path' => 'nullable|string',
        ]);

        $data = $request->except(['image_file']);

        if ($request->hasFile('image_file')) {
            $data['image_path'] = ImageHelper::storeAsWebp($request->file('image_file'), 'attractions', $request->title_es);
        }

        $attraction->update($data);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Atractivo turístico actualizado con éxito.',
                'attraction' => $attraction
            ]);
        }

        return redirect()->back()->with('success', 'Atractivo turístico actualizado con éxito.');
    }

    public function destroy(Attraction $attraction, Request $request)
    {
        $attraction->delete();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Atractivo turístico eliminado con éxito.'
            ]);
        }

        return redirect()->back()->with('success', 'Atractivo turístico eliminado con éxito.');
    }
}
