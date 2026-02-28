<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PagesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Page::updateOrCreate(
            ['slug' => 'home'],
            [
                'name' => 'Home Page',
                'is_published' => true,
                'content' => [
                    'sections' => [
                        [
                            'id' => 'hero_1',
                            'type' => 'hero',
                            'data' => [
                                'title_es' => 'Bienvenido a Hotel Andros',
                                'title_en' => 'Welcome to Hotel Andros',
                                'subtitle_es' => 'El hotel que te hace sentir como en casa',
                                'subtitle_en' => 'The hotel that makes you feel at home',
                                'bg_image' => '/images/branding/hero.png',
                                'overlay_opacity' => 50,
                                'overlay_color' => '#000000',
                                'gap' => 24
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'rooms_1',
                            'type' => 'rooms',
                            'data' => [
                                'title_es' => 'Habitaciones & Suites',
                                'title_en' => 'Rooms & Suites',
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'cafe_1',
                            'type' => 'cafe',
                            'data' => [
                                'cafe_title_es' => 'Sabores Artesanales & Coctelería',
                                'cafe_title_en' => 'Artisan Flavors & Cocktails',
                                'cafe_image' => '/images/gallery/bar.png'
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'location_1',
                            'type' => 'location',
                            'data' => [
                                'location_title_es' => 'Explore el Canal de Panamá',
                                'location_title_en' => 'Explore the Panama Canal',
                                'google_maps_iframe' => 'https://maps.google.com/maps?q=Hotel%20Andros,%20Col%C3%B3n,%20Panam%C3%A1&t=&z=18&ie=UTF8&iwloc=&output=embed',
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'gallery_1',
                            'type' => 'gallery',
                            'data' => [
                                'carousel_title_es' => 'Galería de Momentos',
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'attractions_1',
                            'type' => 'attractions',
                            'data' => [
                                'attractions_title_es' => 'Atracciones Locales',
                            ],
                            'settings' => ['visible' => true]
                        ],
                        [
                            'id' => 'footer_1',
                            'type' => 'footer',
                            'data' => [
                                'hotel_name' => 'Hotel Andros',
                            ],
                            'settings' => ['visible' => true]
                        ]
                    ]
                ]
            ]
        );
    }
}
