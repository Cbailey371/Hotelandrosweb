-- Hotel Andros WEB - Full Database Export for MySQL/phpMyAdmin
-- Includes Schema (CREATE TABLE) and Data (INSERT)

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";
SET FOREIGN_KEY_CHECKS=0;

-- --------------------------------------------------------
-- Table structure for table `migrations`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_22_215721_create_bookings_table', 1),
(5, '2026_01_22_215721_create_galleries_table', 1),
(6, '2026_01_22_215721_create_rooms_table', 1),
(7, '2026_01_22_215721_create_settings_table', 1),
(8, '2026_01_23_004610_add_guests_to_bookings_table', 2),
(9, '2026_01_23_014006_create_attractions_table', 3),
(10, '2026_01_23_225054_add_role_to_users_table', 4),
(11, '2026_01_23_233442_create_gallery_room_table', 5),
(12, '2026_01_24_051258_add_carousel_fields_to_galleries_table', 6),
(13, '2026_01_24_065227_add_country_of_origin_to_bookings_table', 7);

-- --------------------------------------------------------
-- Table structure for table `users`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'reception',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `role`) VALUES
(1, 'Admin Hotel', 'admin@hotel.com', '2026-01-22 22:36:20', '$2y$12$HvEHD/6W3/UMKlZqbVILPuUHcU7.Qq2iChXEMT.dueEpbFLXeCDd2', 'VxCOgUqk8md6kwmXbUKPJrcfosVuOiH0udGEB30cYDNHWuoMG9Y3nFcOQdwL', '2026-01-22 22:36:20', '2026-01-23 22:54:55', 'super_admin');

-- --------------------------------------------------------
-- Table structure for table `settings`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `settings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL DEFAULT 'text',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `settings_key_unique` (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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

-- --------------------------------------------------------
-- Table structure for table `rooms`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name_es` varchar(255) NOT NULL,
  `name_en` varchar(255) NOT NULL,
  `description_es` text DEFAULT NULL,
  `description_en` text DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `capacity` int(11) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `amenities` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`amenities`)),
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `rooms` (`id`, `name_es`, `name_en`, `description_es`, `description_en`, `price`, `capacity`, `status`, `amenities`, `image`, `created_at`, `updated_at`) VALUES
(1,'Habitación Doble Deluxe','Standard Deluxe Double Room','Esta habitación doble es amplia y cuenta con artículos de aseo gratuitos y baño privado con ducha. Dispone de aire acondicionado, armario, caja fuerte, suelo de baldosa. Dispone de 2 camas.','Providing free toiletries, this double room includes a private bathroom with a shower. The spacious double room provides air conditioning, a wardrobe, a safe deposit box, a tiled floor. The unit offers 2 beds.',50,4,'active','[\"WiFi\",\"TV\"]','/storage/gallery/oprUj3cLHAFhny4BeCS2vg3eAm5DhgjH8vI5ueLu.webp','2026-01-22 22:36:20','2026-01-24 07:54:12'),
(2,'Habitación Deluxe con cama extragrande','Deluxe Room with King Bed','Los huéspedes disfrutarán de una experiencia especial, ya que la habitación doble cuenta con bañera de hidromasaje. Incluye artículos de aseo gratuitos y baño privado con bañera. La espaciosa habitación doble cuenta con aire acondicionado, armario, caja fuerte y suelo de baldosa. El alojamiento cuenta con 1 cama.','Guests will have a special experience as the double room features a hot tub. Providing free toiletries, this double room includes a private bathroom with a bath. The spacious air-conditioned double room, a wardrobe, a safe deposit box and a tiled floor. The unit offers 1 bed.',55,2,'active','[\"WiFi\",\"TV\"]','/storage/gallery/9tLJRDNZF6VZJiAvnpVdjZBqzDLXlT5G5xYcj0yG.webp','2026-01-22 22:36:20','2026-01-24 07:54:20');

-- --------------------------------------------------------
-- Table structure for table `bookings`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `check_in` date NOT NULL,
  `check_out` date NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `guests` int(11) NOT NULL DEFAULT 1,
  `country` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `bookings_room_id_foreign` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `galleries`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `galleries` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_es` varchar(255) DEFAULT NULL,
  `title_en` varchar(255) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `show_in_carousel` tinyint(1) NOT NULL DEFAULT 0,
  `carousel_order` int(11) NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `attractions`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `attractions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `title_es` varchar(255) NOT NULL,
  `title_en` varchar(255) NOT NULL,
  `description_es` text NOT NULL,
  `description_en` text NOT NULL,
  `image_path` varchar(255) DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Table structure for table `gallery_room`
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `gallery_room` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `gallery_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `gallery_room_gallery_id_foreign` (`gallery_id`),
  KEY `gallery_room_room_id_foreign` (`room_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Authentication Tables (Laravel default)
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Constraints
ALTER TABLE `bookings` ADD CONSTRAINT `bookings_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;
ALTER TABLE `gallery_room` ADD CONSTRAINT `gallery_room_gallery_id_foreign` FOREIGN KEY (`gallery_id`) REFERENCES `galleries` (`id`) ON DELETE CASCADE;
ALTER TABLE `gallery_room` ADD CONSTRAINT `gallery_room_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

SET FOREIGN_KEY_CHECKS=1;
