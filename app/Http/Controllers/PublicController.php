<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Setting;
use App\Models\Gallery;
use App\Models\Attraction;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function index()
    {
        $data = \Illuminate\Support\Facades\Cache::remember('home_page_data', 86400, function () {
            $page = \App\Models\Page::where('slug', 'home')->first();
            
            // Si la página no existe, retornamos null o fallback dentro del cache
            if (!$page) return null;

            return [
                'page' => $page,
                'rooms' => Room::where('status', 'active')->with(['galleries'])->get(),
                'settings' => Setting::pluck('value', 'key')->all(),
                'gallery' => Gallery::orderBy('order')->get(),
                'attractions' => Attraction::orderBy('order')->get(),
                'carouselImages' => Gallery::where('show_in_carousel', true)->orderBy('carousel_order')->get(),
                'cafeImages' => Gallery::where('show_in_cafe', true)->orderBy('cafe_order')->get(),
            ];
        });

        if (!$data) {
            // Manejo si no hay página home
            return redirect()->route('welcome');
        }

        $activePromotion = \App\Models\Promotion::where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('starts_at')->orWhere('starts_at', '<=', now());
            })
            ->where(function ($query) {
                $query->whereNull('ends_at')->orWhere('ends_at', '>=', now());
            })
            ->latest()
            ->first();

        $disableFooter = true;
        
        return view('page', array_merge($data, [
            'disableFooter' => $disableFooter,
            'activePromotion' => $activePromotion
        ]));
    }
}
