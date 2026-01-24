-- Script de importación de datos para MySQL (phpMyAdmin)
-- Generado para el proyecto Hotel Andros WEB

SET FOREIGN_KEY_CHECKS=0;

-- Limpiar tablas antes de insertar para evitar duplicados (Opcional, comentar si no es necesario)
-- SET AUTO_INCREMENT = 1;

-- IMPORTANTE: Ejecutar 'php artisan migrate' en el servidor primero para crear las tablas.

-- Datos de Usuarios
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1,'Admin Hotel','admin@hotel.com','2026-01-22 22:36:20','$2y$12$HvEHD/6W3/UMKlZqbVILPuUHcU7.Qq2iChXEMT.dueEpbFLXeCDd2','VxCOgUqk8md6kwmXbUKPJrcfosVuOiH0udGEB30cYDNHWuoMG9Y3nFcOQdwL','2026-01-22 22:36:20','2026-01-23 22:54:55','super_admin');

-- Datos de Configuraciones (Settings)
INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1,'hotel_name','Hotel Andros','text','2026-01-22 22:36:20','2026-01-22 22:57:10'),
(2,'hotel_email','reservas@hotelandros.com','text','2026-01-22 22:36:20','2026-01-24 07:06:06'),
(3,'hotel_phone','+1 (555) 987-6543','text','2026-01-22 22:36:20','2026-01-22 22:36:20'),
(4,'hotel_whatsapp','(507) 6939-1880','text','2026-01-22 22:36:20','2026-01-24 07:06:06'),
(5,'hotel_address','Av Herrera, entre, C. 9na y 10, Colón','text','2026-01-22 22:36:20','2026-01-23 02:03:51'),
(6,'primary_color','#208B3A','color','2026-01-22 22:36:20','2026-01-23 18:45:14'),
(7,'secondary_color','#2D70B6','color','2026-01-22 22:36:20','2026-01-23 18:45:14'),
(8,'hero_title_es','<h1><strong>BIENVENIDO A HOTEL ANDROS</strong></h1>','text','2026-01-22 22:36:20','2026-01-24 07:08:23'),
(9,'hero_title_en','<h1><strong>WELCOME TO HOTEL ANDROS</strong></h1>','text','2026-01-22 22:36:20','2026-01-24 07:08:23'),
(10,'hero_subtitle_es','<h2>El hotel que te hace sentir como en casa</h2>','text','2026-01-22 22:36:20','2026-01-23 22:06:19'),
(11,'hero_subtitle_en','<h2>The hotel that makes you feel at home</h2>','text','2026-01-22 22:36:20','2026-01-23 22:06:19'),
(12,'hero_image','/storage/gallery/oaMXJcJhhUfXDnQDrIL4UuizoVWIafx1Q6uI0VNT.webp','image','2026-01-22 22:36:20','2026-01-24 14:44:43');

-- Datos de Habitaciones (Rooms)
INSERT INTO `rooms` (`id`, `name_es`, `name_en`, `description_es`, `description_en`, `price`, `capacity`, `status`, `amenities`, `image`, `created_at`, `updated_at`) VALUES
(1,'Habitación Doble Deluxe','Standard Deluxe Double Room','Esta habitación doble es amplia y cuenta con artículos de aseo gratuitos y baño privado con ducha. Dispone de aire acondicionado, armario, caja fuerte, suelo de baldosa. Dispone de 2 camas.','Providing free toiletries, this double room includes a private bathroom with a shower. The spacious double room provides air conditioning, a wardrobe, a safe deposit box, a tiled floor. The unit offers 2 beds.',50,4,'active','[\"WiFi\",\"TV\"]','/storage/gallery/oprUj3cLHAFhny4BeCS2vg3eAm5DhgjH8vI5ueLu.webp','2026-01-22 22:36:20','2026-01-24 07:54:12'),
(2,'Habitación Deluxe con cama extragrande','Deluxe Room with King Bed','Los huéspedes disfrutarán de una experiencia especial, ya que la habitación doble cuenta con bañera de hidromasaje. Incluye artículos de aseo gratuitos y baño privado con bañera. La espaciosa habitación doble cuenta con aire acondicionado, armario, caja fuerte y suelo de baldosa. El alojamiento cuenta con 1 cama.','Guests will have a special experience as the double room features a hot tub. Providing free toiletries, this double room includes a private bathroom with a bath. The spacious air-conditioned double room, a wardrobe, a safe deposit box and a tiled floor. The unit offers 1 bed.',55,2,'active','[\"WiFi\",\"TV\"]','/storage/gallery/9tLJRDNZF6VZJiAvnpVdjZBqzDLXlT5G5xYcj0yG.webp','2026-01-22 22:36:20','2026-01-24 07:54:20');

-- Datos de Galería
INSERT INTO `galleries` (`id`, `title_es`, `title_en`, `image_path`, `order`, `created_at`, `updated_at`, `show_in_carousel`, `carousel_order`) VALUES
(2,NULL,NULL,'/images/gallery/pool.png',2,'2026-01-23 01:31:58','2026-01-23 01:31:58',0,0),
(5,'FuerteSanLorenzo.jpg','FuerteSanLorenzo.jpg','/storage/gallery/Bu24wohnEQ2s1oYUJJag6qJPLaBmnsALYViMVuFi.webp',5,'2026-01-23 20:15:55','2026-01-24 06:01:34',0,0);

-- Atracciones Locales
INSERT INTO `attractions` (`id`, `title_es`, `title_en`, `description_es`, `description_en`, `image_path`, `order`, `created_at`, `updated_at`) VALUES
(1,'Centro de Visitantes de Agua Clara','Centro de Visitantes de Agua Clara','<p>El Centro de Visitantes de Agua Clara, en la provincia de Colón.</p>','<p>The Agua Clara Visitor Center, in the province of Colón.</p>','/storage/attractions/WOBJ12P5WBZBpiGUxpK9TSLUpakHaHSIigFuPEnp.webp',1,'2026-01-23 01:41:45','2026-01-23 20:41:04');

SET FOREIGN_KEY_CHECKS=1;
