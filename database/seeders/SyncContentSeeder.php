<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Page;
use App\Models\Setting;

class SyncContentSeeder extends Seeder
{
    public function run()
    {
        $settings = Setting::all()->pluck('value', 'key');

        $sections = [
            // 1. Hero Section
            [
                'id' => 'hero_1',
                'type' => 'hero',
                'data' => [
                    'title_es' => $settings['hero_title_es'] ?? 'HOTEL ANDROS',
                    'title_en' => $settings['hero_title_en'] ?? 'WELCOME TO HOTEL ANDROS',
                    'subtitle_es' => $settings['hero_subtitle_es'] ?? 'El hotel que te hace sentir como en casa',
                    'subtitle_en' => $settings['hero_subtitle_en'] ?? 'The hotel that makes you feel at home',
                    'bg_image' => $settings['hero_image'] ?? '/images/branding/hero.png',
                    'overlay_opacity' => $settings['hero_overlay_opacity'] ?? 50,
                    'overlay_color' => $settings['hero_overlay_color'] ?? '#000000',
                    'gap' => $settings['hero_gap'] ?? 0
                ],
                'settings' => ['visible' => true]
            ],
            // 2. Rooms & Suites (Titulo y Subtitulo)
            [
                'id' => 'rooms_1',
                'type' => 'rooms',
                'data' => [
                    'title_es' => $settings['rooms_title_es'] ?? 'Habitaciones & Suites',
                    'title_en' => $settings['rooms_title_en'] ?? 'Rooms & Suites',
                    'description_es' => $settings['rooms_description_es'] ?? '',
                    'description_en' => $settings['rooms_description_en'] ?? '',
                ],
                'settings' => ['visible' => true]
            ],
            // 3. Andros Café
            [
                'id' => 'cafe_1',
                'type' => 'cafe',
                'data' => [
                    'cafe_title_es' => $settings['cafe_title_es'] ?? 'Sabores Artesanales',
                    'cafe_title_en' => $settings['cafe_title_en'] ?? 'Artisanal Flavors',
                    'cafe_description_es' => $settings['cafe_description_es'] ?? '',
                    'cafe_description_en' => $settings['cafe_description_en'] ?? '',
                    'cafe_image' => $settings['cafe_image'] ?? '/images/gallery/bar.png'
                ],
                'settings' => ['visible' => true]
            ],
            // 4. Ubicación
            [
                'id' => 'location_1',
                'type' => 'location',
                'data' => [
                    'location_title_es' => $settings['location_title_es'] ?? 'Explore el Canal de Panamá',
                    'location_title_en' => $settings['location_title_en'] ?? 'Explore the Panama Canal',
                    'location_description_es' => $settings['location_description_es'] ?? '',
                    'location_description_en' => $settings['location_description_en'] ?? '',
                    'location_badge_es' => $settings['location_badge_es'] ?? 'Ubicación',
                    'location_badge_en' => $settings['location_badge_en'] ?? 'Location',
                    'google_maps_iframe' => $settings['google_maps_iframe'] ?? '',
                ],
                'settings' => ['visible' => true]
            ],
            // 5. Carrusel (Gallery)
            [
                'id' => 'gallery_1',
                'type' => 'gallery',
                'data' => [
                    'carousel_title_es' => $settings['carousel_title_es'] ?? 'Galería de Momentos',
                    'carousel_title_en' => $settings['carousel_title_en'] ?? 'Moments Gallery',
                    'carousel_badge_es' => $settings['carousel_badge_es'] ?? 'Experiencia Visual',
                    'carousel_badge_en' => $settings['carousel_badge_en'] ?? 'Visual Experience',
                ],
                'settings' => ['visible' => true]
            ],
            // 6. Local Attractions
            [
                'id' => 'attractions_1',
                'type' => 'attractions',
                'data' => [
                    'attractions_title_es' => $settings['attractions_title_es'] ?? 'Atracciones Locales',
                    'attractions_title_en' => $settings['attractions_title_en'] ?? 'Local Attractions',
                    'attractions_badge_es' => $settings['attractions_badge_es'] ?? 'EXPLORA PANAMA',
                    'attractions_badge_en' => $settings['attractions_badge_en'] ?? 'EXPLORE PANAMA',
                ],
                'settings' => ['visible' => true]
            ],
            // 7. Pie de Pagina (con políticas)
            [
                'id' => 'footer_1',
                'type' => 'footer',
                'data' => [
                    'hotel_name' => $settings['hotel_name'] ?? 'Hotel Andros',
                    'footer_description_es' => $settings['footer_description_es'] ?? '',
                    'footer_description_en' => $settings['footer_description_en'] ?? '',
                    'footer_contact_description_es' => $settings['footer_contact_description_es'] ?? '',
                    'footer_contact_description_en' => $settings['footer_contact_description_en'] ?? '',
                    'footer_copyright_es' => $settings['footer_copyright_es'] ?? '',
                    'footer_copyright_en' => $settings['footer_copyright_en'] ?? '',
                    // Policies
                    'footer_policies_es' => $settings['footer_policies_es'] ?? '',
                    'footer_policies_en' => $settings['footer_policies_en'] ?? '',
                ],
                'settings' => ['visible' => true]
            ]
        ];

        Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'name' => 'Home Page',
                'content' => ['sections' => $sections]
            ]
        );

        $this->command->info('Synced content from Settings to Page JSON with updated order.');
    }
}
