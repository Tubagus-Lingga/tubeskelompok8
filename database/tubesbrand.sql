-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 10, 2026 at 12:12 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tubesbrand`
--

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2025_11_28_184615_create_products_table', 1),
(5, '2025_11_28_185439_add_role_to_users_table', 1),
(6, '2025_12_09_144557_add_slug_to_products_table', 1),
(7, '2025_12_10_062352_add_category_to_products_table', 1),
(8, '2025_12_10_101533_create_orders_table', 1),
(9, '2025_12_10_101616_create_order_items_table', 1),
(10, '2025_12_10_101709_create_payments_table', 1),
(11, '2025_12_10_105441_add_handling_status_to_orders_table', 1),
(12, '2025_12_10_204404_create_product_variants_table', 1),
(13, '2025_12_10_204642_alter_product_variants_add_size_stock_unique', 1),
(14, '2025_12_10_210045_add_size_to_order_items_table', 1),
(15, '2025_12_18_173720_add_shipping_to_orders_table', 1),
(16, '2025_12_21_165645_add_detailed_address_to_orders_table', 1);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `code` varchar(255) NOT NULL,
  `total_amount` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_payment',
  `alamat` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `district` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `telp` varchar(30) DEFAULT NULL,
  `handling_status` varchar(255) NOT NULL DEFAULT 'new',
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `size` varchar(10) NOT NULL,
  `name` varchar(255) NOT NULL,
  `qty` int(10) UNSIGNED NOT NULL,
  `price` bigint(20) UNSIGNED NOT NULL,
  `subtotal` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `provider` varchar(255) NOT NULL DEFAULT 'manual',
  `reference` varchar(255) DEFAULT NULL,
  `amount` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `paid_at` timestamp NULL DEFAULT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `price` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `category` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `price`, `stock`, `image`, `category`, `created_at`, `updated_at`) VALUES
(1, 'Jeans “SAINT Dept de la Galerie Rhinestone”', 'jeans-saint-dept-de-la-galerie-rhinestone', 'Celana jeans panjang warna abu washed dengan desain statement. Dilengkapi patch tulisan besar di bagian depan serta taburan rhinestone/batu kecil yang memberikan efek berkilau. Cocok untuk streetwear premium dan outfit standout.', 499000, 32, '1768042143_celana2.jpg', 'celana', '2026-01-10 03:49:03', '2026-01-10 03:53:49'),
(2, 'Zip Hoodie “SAINT Cross Distressed Blue”', 'zip-hoodie-saint-cross-distressed-blue', 'Hoodie zip-up warna biru washed dengan grafis cross besar di bagian depan. Dilengkapi detail distressed (sobek) di lengan dan aksen print menyeluruh yang memberi kesan edgy dan premium streetwear.', 389000, 32, '1768042234_hoodie1.jpg', 'hoodie', '2026-01-10 03:50:34', '2026-01-10 03:53:40'),
(3, 'Hoodie “Core Logo Black”', 'hoodie-core-logo-black', 'Hoodie hitam polos dengan logo kecil di dada dan bagian hood. Desain clean dan minimal, cocok untuk daily wear dan layering outfit streetwear.', 299000, 28, '1768042279_hoodie2.jpg', 'hoodie', '2026-01-10 03:51:19', '2026-01-10 03:53:28'),
(4, 'Layered Tee “Gothic Archive”', 'layered-tee-gothic-archive', 'Kaos layered dengan tampilan kaos hitam berlengan pendek yang dipadukan inner lengan panjang abu bermotif tribal. Dilengkapi grafis gothic dan detail stud di area kerah, menciptakan look grunge-modern.', 219000, 38, '1768042396_kaos1.jpg', 'kaos', '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(5, 'T-Shirt “Land of Pain”', 't-shirt-land-of-pain', 'Kaos putih dengan grafis ilustrasi pistol hitam di bagian depan. Mengusung tema street–grunge yang kuat dan bold.', 159000, 70, '1768042471_kaos2.jpg', 'kaos', '2026-01-10 03:54:31', '2026-01-10 03:54:31'),
(6, 'Manchester United Retro Jersey “Umbro Sharp”', 'manchester-united-retro-jersey-umbro-sharp', 'Jersey sepak bola retro Manchester United dengan sponsor SHARP dan brand Umbro. Desain klasik ikonik era 90-an, cocok untuk kolektor dan pecinta football culture.', 349000, 32, '1768042514_kaos3.jpg', 'kaos', '2026-01-10 03:55:14', '2026-01-10 03:55:14'),
(7, 'Stüssy “Old Skool Flavor Tee”', 'stussy-old-skool-flavor-tee', 'Kaos putih dengan grafis skateboarder khas Stüssy. Menampilkan nuansa skate culture klasik yang timeless dan mudah dipadukan dengan outfit kasual.', 199000, 60, '1768042554_kaos4.jpg', 'kaos', '2026-01-10 03:55:54', '2026-01-10 03:55:54'),
(8, 'Jeans “Script Noir Raw Hem”', 'jeans-script-noir-raw-hem', 'Celana jeans hitam washed dengan tulisan script di kedua kaki. Bagian bawah unfinished (raw hem) memberikan kesan grunge dan streetwear modern.', 459000, 32, '1768042591_celana1.jpg', 'celana', '2026-01-10 03:56:31', '2026-01-10 03:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `size` varchar(10) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `size`, `stock`, `created_at`, `updated_at`) VALUES
(13, 4, 'S', 6, '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(14, 4, 'M', 10, '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(15, 4, 'L', 10, '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(16, 4, 'XL', 6, '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(17, 4, 'XXL', 6, '2026-01-10 03:53:16', '2026-01-10 03:53:16'),
(18, 3, 'L', 0, '2026-01-10 03:53:28', '2026-01-10 03:53:28'),
(19, 3, 'M', 12, '2026-01-10 03:53:28', '2026-01-10 03:53:28'),
(20, 3, 'S', 8, '2026-01-10 03:53:28', '2026-01-10 03:53:28'),
(21, 3, 'XL', 8, '2026-01-10 03:53:28', '2026-01-10 03:53:28'),
(22, 2, 'L', 10, '2026-01-10 03:53:40', '2026-01-10 03:53:40'),
(23, 2, 'M', 10, '2026-01-10 03:53:40', '2026-01-10 03:53:40'),
(24, 2, 'S', 6, '2026-01-10 03:53:40', '2026-01-10 03:53:40'),
(25, 2, 'XL', 6, '2026-01-10 03:53:40', '2026-01-10 03:53:40'),
(26, 1, 'L', 10, '2026-01-10 03:53:49', '2026-01-10 03:53:49'),
(27, 1, 'M', 10, '2026-01-10 03:53:49', '2026-01-10 03:53:49'),
(28, 1, 'S', 6, '2026-01-10 03:53:49', '2026-01-10 03:53:49'),
(29, 1, 'XL', 6, '2026-01-10 03:53:49', '2026-01-10 03:53:49'),
(30, 5, 'S', 15, '2026-01-10 03:54:31', '2026-01-10 03:54:31'),
(31, 5, 'M', 20, '2026-01-10 03:54:31', '2026-01-10 03:54:31'),
(32, 5, 'L', 20, '2026-01-10 03:54:31', '2026-01-10 03:54:31'),
(33, 5, 'XL', 15, '2026-01-10 03:54:31', '2026-01-10 03:54:31'),
(34, 6, 'S', 6, '2026-01-10 03:55:14', '2026-01-10 03:55:14'),
(35, 6, 'M', 10, '2026-01-10 03:55:14', '2026-01-10 03:55:14'),
(36, 6, 'L', 10, '2026-01-10 03:55:14', '2026-01-10 03:55:14'),
(37, 6, 'XL', 6, '2026-01-10 03:55:14', '2026-01-10 03:55:14'),
(38, 7, 'S', 12, '2026-01-10 03:55:54', '2026-01-10 03:55:54'),
(39, 7, 'M', 18, '2026-01-10 03:55:54', '2026-01-10 03:55:54'),
(40, 7, 'L', 18, '2026-01-10 03:55:54', '2026-01-10 03:55:54'),
(41, 7, 'XL', 12, '2026-01-10 03:55:54', '2026-01-10 03:55:54'),
(42, 8, 'S', 6, '2026-01-10 03:56:31', '2026-01-10 03:56:31'),
(43, 8, 'M', 10, '2026-01-10 03:56:31', '2026-01-10 03:56:31'),
(44, 8, 'L', 10, '2026-01-10 03:56:31', '2026-01-10 03:56:31'),
(45, 8, 'XL', 6, '2026-01-10 03:56:31', '2026-01-10 03:56:31');

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'Raylizdo', 'raylizdo@gmail.com', NULL, '$2y$12$RUj.5J9K4OHec3H4xVQj8uxqU1tn.gseXJUIbA62jEvt9imTbAHpW', 'admin', NULL, '2026-01-10 03:33:14', '2026-01-10 03:33:14');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_code_unique` (`code`),
  ADD KEY `orders_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payments_order_id_foreign` (`order_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_product_id_size_unique` (`product_id`,`size`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
