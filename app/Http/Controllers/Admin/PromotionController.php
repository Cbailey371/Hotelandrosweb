<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PromotionController extends Controller
{
    public function index()
    {
        $promotions = Promotion::latest()->paginate(10);
        return view('admin.promotions.index', compact('promotions'));
    }

    public function create()
    {
        return view('admin.promotions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            // Aumentamos a 10MB para tolerar clips cortos, agregamos webm y mp4
            'media_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,webm,mp4|max:10240',
            'youtube_id' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if (!$request->hasFile('media_path') && !$request->filled('youtube_id')) {
            return back()->withInput()->withErrors(['media_path' => 'Debes proporcionar un Flyer (Imagen/Video) o un ID de YouTube.']);
        }

        $data = $request->except(['media_path', 'youtube_id']);

        // Extraer ID de Youtube por si pegan la URL completa
        if ($request->filled('youtube_id')) {
            $youtubeId = $request->input('youtube_id');
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeId, $match);
            $data['youtube_id'] = $match[1] ?? $youtubeId;
            $data['media_type'] = 'youtube';
            $data['media_path'] = null; // Ignorar archivo si hay youtube
        } else if ($request->hasFile('media_path')) {
            $file = $request->file('media_path');
            $path = $file->store('promotions', 'public');
            $data['media_path'] = '/storage/' . $path;

            // Determinar si es un video (WebM/MP4) o imagen por su extensión guardada
            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, ['webm', 'mp4'])) {
                $data['media_type'] = 'video';
            } else {
                $data['media_type'] = 'image';
            }

            $data['youtube_id'] = null;
        }

        $data['is_active'] = $request->has('is_active');

        Promotion::create($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción creada exitosamente.');
    }

    public function edit(Promotion $promotion)
    {
        return view('admin.promotions.edit', compact('promotion'));
    }

    public function update(Request $request, Promotion $promotion)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'media_path' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp,webm,mp4|max:10240',
            'youtube_id' => 'nullable|string|max:255',
            'link_url' => 'nullable|url',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if (!$request->hasFile('media_path') && !$request->filled('youtube_id') && !$promotion->media_path && !$promotion->youtube_id) {
            return back()->withInput()->withErrors(['media_path' => 'Debes mantener o proporcionar un Flyer (Imagen/Video) o un ID de YouTube.']);
        }

        $data = $request->except(['media_path', 'youtube_id']);

        if ($request->filled('youtube_id')) {
            $youtubeId = $request->input('youtube_id');
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $youtubeId, $match);
            $data['youtube_id'] = $match[1] ?? $youtubeId;
            $data['media_type'] = 'youtube';

            // Si previamente era imagen o video nativo, borrarla fisica
            if ($promotion->media_path && in_array($promotion->media_type, ['image', 'video'])) {
                $oldPath = str_replace('/storage/', '', $promotion->media_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            $data['media_path'] = null;

        } else if ($request->hasFile('media_path')) {
            // Delete old file if exists
            if ($promotion->media_path && in_array($promotion->media_type, ['image', 'video'])) {
                $oldPath = str_replace('/storage/', '', $promotion->media_path);
                if (Storage::disk('public')->exists($oldPath)) {
                    Storage::disk('public')->delete($oldPath);
                }
            }
            // Store new
            $file = $request->file('media_path');
            $path = $file->store('promotions', 'public');
            $data['media_path'] = '/storage/' . $path;

            // Descubrir extensión
            $extension = strtolower($file->getClientOriginalExtension());
            if (in_array($extension, ['webm', 'mp4'])) {
                $data['media_type'] = 'video';
            } else {
                $data['media_type'] = 'image';
            }

            $data['youtube_id'] = null;
        }

        $data['is_active'] = $request->has('is_active');

        $promotion->update($data);

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción actualizada.');
    }

    public function destroy(Promotion $promotion)
    {
        $oldPath = str_replace('/storage/', '', $promotion->media_path);
        if (Storage::disk('public')->exists($oldPath)) {
            Storage::disk('public')->delete($oldPath);
        }

        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción eliminada.');
    }

    public function toggleActive(Promotion $promotion)
    {
        $promotion->update(['is_active' => !$promotion->is_active]);
        return back()->with('success', 'Estado de la promoción actualizado.');
    }
}
