<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Admin Hotel',
            'email' => 'admin@hotel.com',
            'password' => bcrypt('password'),
            'email_verified_at' => now(),
        ]);

        // Rooms
        \App\Models\Room::create([
            'name_es' => 'Habitación Estándar',
            'name_en' => 'Standard Room',
            'description_es' => 'Habitación acogedora con diseño escandinavo, ideal para estancias cortas.',
            'description_en' => 'Cozy room with Scandinavian design, ideal for short stays.',
            'price' => 145.00,
            'capacity' => 2,
            'status' => 'active',
            'amenities' => ['WiFi', 'TV', 'Escritorio'],
            'image' => '/images/rooms/standard.png',
        ]);

        \App\Models\Room::create([
            'name_es' => 'Suite Deluxe con Vista al Mar',
            'name_en' => 'Deluxe Ocean Suite',
            'description_es' => 'Lujosa suite con ventanales de piso a techo y vista panorámica al océano.',
            'description_en' => 'Luxurious suite with floor-to-ceiling windows and panoramic ocean views.',
            'price' => 280.00,
            'capacity' => 2,
            'status' => 'active',
            'amenities' => ['Cama King', 'Vista al Mar', 'Minibar Premium'],
            'image' => '/images/rooms/deluxe.png',
        ]);

        \App\Models\Room::create([
            'name_es' => 'Executive Suite living',
            'name_en' => 'Executive Suite Living',
            'description_es' => 'Espaciosa suite con área de estar independiente y acabados contemporáneos.',
            'description_en' => 'Spacious suite with independent living area and contemporary finishes.',
            'price' => 450.00,
            'capacity' => 3,
            'status' => 'active',
            'amenities' => ['Área de Estar', 'Balcón', 'Servicio al Cuarto 24/7'],
            'image' => '/images/rooms/suite.png',
        ]);

        // Gallery
        \App\Models\Gallery::create([
            'title_es' => 'Nuestro Exclusivo Bar',
            'title_en' => 'Our Exclusive Bar',
            'image_path' => '/images/gallery/bar.png',
            'order' => 1,
        ]);

        \App\Models\Gallery::create([
            'title_es' => 'Piscina Infinity al Atardecer',
            'title_en' => 'Infinity Pool at Sunset',
            'image_path' => '/images/gallery/pool.png',
            'order' => 2,
        ]);

        // Settings
        $settings = [
            ['key' => 'hotel_name', 'value' => 'The Palm Collective', 'type' => 'text'],
            ['key' => 'hotel_email', 'value' => 'reservas@palmcollective.com', 'type' => 'text'],
            ['key' => 'hotel_phone', 'value' => '+1 (555) 987-6543', 'type' => 'text'],
            ['key' => 'hotel_whatsapp', 'value' => '15559876543', 'type' => 'text'],
            ['key' => 'hotel_address', 'value' => 'Calle Paraíso 456, Playa del Carmen', 'type' => 'text'],
            ['key' => 'primary_color', 'value' => '#137fec', 'type' => 'color'],
            ['key' => 'secondary_color', 'value' => '#4c739a', 'type' => 'color'],
            ['key' => 'hero_title_es', 'value' => 'Lujo y Confort frente al Mar', 'type' => 'text'],
            ['key' => 'hero_title_en', 'value' => 'Luxury and Comfort facing the Sea', 'type' => 'text'],
            ['key' => 'hero_subtitle_es', 'value' => 'Escapa a un oasis de tranquilidad diseñado para los viajeros más exigentes.', 'type' => 'text'],
            ['key' => 'hero_subtitle_en', 'value' => 'Escape to an oasis of tranquility designed for the most demanding travelers.', 'type' => 'text'],
            ['key' => 'hero_image', 'value' => '/images/branding/hero.png', 'type' => 'image'],
        ];

        foreach ($settings as $setting) {
            \App\Models\Setting::create($setting);
        }

        // Llamar al seeder de Páginas para inicializar la ruta 'home' requerida por el editor visual.
        $this->call(PagesTableSeeder::class);
    }
}
