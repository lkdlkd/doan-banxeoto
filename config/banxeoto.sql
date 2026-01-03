-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 03, 2026 lúc 04:25 AM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `banxeoto`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `brands`
--

INSERT INTO `brands` (`id`, `name`, `logo`, `description`, `created_at`) VALUES
(1, 'Mercedes-Benz', 'https://www.carlogos.org/car-logos/mercedes-benz-logo.png', 'Th????ng hi???u xe sang ?????c', '2026-01-02 07:00:58'),
(2, 'BMW', 'https://www.carlogos.org/car-logos/bmw-logo.png', 'Bayerische Motoren Werke AG', '2026-01-02 07:00:58'),
(3, 'Audi', 'https://www.carlogos.org/car-logos/audi-logo.png', 'Th????ng hi???u xe sang ?????c', '2026-01-02 07:00:58'),
(4, 'Porsche', 'https://www.carlogos.org/car-logos/porsche-logo.png', 'Th????ng hi???u xe th??? thao cao c???p ?????c', '2026-01-02 07:00:58'),
(5, 'Ferrari', 'https://www.carlogos.org/car-logos/ferrari-logo.png', 'H??ng si??u xe Italia', '2026-01-02 07:00:58'),
(6, 'Lamborghini', 'https://www.carlogos.org/car-logos/lamborghini-logo.png', 'H??ng si??u xe Italia', '2026-01-02 07:00:58'),
(7, 'McLaren', 'https://www.carlogos.org/car-logos/mclaren-logo.png', 'H??ng si??u xe Anh Qu???c', '2026-01-02 07:00:58'),
(8, 'Bentley', 'https://www.carlogos.org/car-logos/bentley-logo.png', 'Xe si??u sang Anh Qu???c', '2026-01-02 07:00:58'),
(9, 'Rolls-Royce', 'https://www.carlogos.org/car-logos/rolls-royce-logo.png', 'Xe si??u sang Anh Qu???c', '2026-01-02 07:00:58'),
(10, 'Maserati', 'https://www.carlogos.org/car-logos/maserati-logo.png', 'Xe sang th??? thao Italia', '2026-01-02 07:00:58'),
(11, 'Lexus', 'https://www.carlogos.org/car-logos/lexus-logo.png', 'Th????ng hi???u xe sang Nh???t B???n', '2026-01-02 07:00:58'),
(12, 'Toyota', 'https://www.carlogos.org/car-logos/toyota-logo.png', 'Th????ng hi???u xe Nh???t B???n h??ng ?????u', '2026-01-02 07:00:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cars`
--

CREATE TABLE `cars` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `year` int(11) DEFAULT NULL,
  `mileage` int(11) DEFAULT NULL,
  `fuel` enum('gasoline','diesel','electric') DEFAULT NULL,
  `transmission` enum('manual','automatic') DEFAULT NULL,
  `color` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `status` enum('available','sold') DEFAULT 'available',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cars`
--

INSERT INTO `cars` (`id`, `name`, `brand_id`, `category_id`, `price`, `year`, `mileage`, `fuel`, `transmission`, `color`, `description`, `status`, `created_at`) VALUES
(1, 'AMG GT 63 S', 1, 3, 12500000000.00, 2024, 0, 'gasoline', 'automatic', '??en', 'Mercedes-AMG GT 63 S 4MATIC+ v???i ?????ng c?? V8 4.0L twin-turbo 639 m?? l???c', 'available', '2026-01-02 07:00:58'),
(2, 'S-Class S450', 1, 1, 5200000000.00, 2024, 0, 'gasoline', 'automatic', 'Tr???ng', 'Mercedes-Benz S450 4MATIC Luxury sedan flagship', 'available', '2026-01-02 07:00:58'),
(3, 'GLE 450 AMG', 1, 2, 4800000000.00, 2024, 5000, 'gasoline', 'automatic', 'X??m', 'Mercedes GLE 450 4MATIC Coupe AMG Line', 'available', '2026-01-02 07:00:58'),
(4, 'M8 Competition', 2, 3, 13200000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh San Marino', 'BMW M8 Competition Coupe v???i ?????ng c?? V8 4.4L 617 m?? l???c', 'available', '2026-01-02 07:00:58'),
(5, 'X7 xDrive40i', 2, 2, 6800000000.00, 2024, 3000, 'gasoline', 'automatic', '??en Sapphire', 'BMW X7 xDrive40i M Sport 7 ch??? luxury SUV', 'available', '2026-01-02 07:00:58'),
(6, '7 Series 740i', 2, 1, 5900000000.00, 2025, 0, 'gasoline', 'automatic', 'Tr???ng Alpine', 'BMW 740i M Sport flagship sedan', 'available', '2026-01-02 07:00:58'),
(7, 'RS7 Sportback', 3, 1, 8900000000.00, 2024, 0, 'gasoline', 'automatic', 'X??m Nardo', 'Audi RS7 Sportback v???i ?????ng c?? V8 4.0L TFSI 621 m?? l???c', 'available', '2026-01-02 07:00:58'),
(8, 'Q8 55 TFSI', 3, 2, 5500000000.00, 2024, 8000, 'gasoline', 'automatic', '??en', 'Audi Q8 55 TFSI quattro S line coupe SUV', 'available', '2026-01-02 07:00:58'),
(9, 'e-tron GT', 3, 1, 6500000000.00, 2024, 2000, 'electric', 'automatic', 'Xanh Kemora', 'Audi e-tron GT quattro ??i???n cao c???p', 'available', '2026-01-02 07:00:58'),
(10, '911 Turbo S', 4, 5, 15800000000.00, 2025, 0, 'gasoline', 'automatic', 'V??ng Racing', 'Porsche 911 Turbo S v???i ?????ng c?? flat-6 3.8L 640 m?? l???c', 'available', '2026-01-02 07:00:58'),
(11, 'Cayenne Turbo GT', 4, 2, 12500000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh Gentian', 'Porsche Cayenne Turbo GT v???i 640 m?? l???c', 'available', '2026-01-02 07:00:58'),
(12, 'Panamera 4S E-Hybrid', 4, 1, 8200000000.00, 2024, 5000, 'gasoline', 'automatic', '??en Jet', 'Porsche Panamera 4S E-Hybrid Sport Turismo', 'available', '2026-01-02 07:00:58'),
(13, 'F8 Tributo', 5, 5, 21500000000.00, 2024, 0, 'gasoline', 'automatic', '????? Rosso Corsa', 'Ferrari F8 Tributo v???i ?????ng c?? V8 3.9L twin-turbo 720 m?? l???c', 'available', '2026-01-02 07:00:58'),
(14, 'SF90 Stradale', 5, 5, 35000000000.00, 2024, 0, 'gasoline', 'automatic', '????? Rosso Scuderia', 'Ferrari SF90 Stradale hybrid 1000 m?? l???c', 'available', '2026-01-02 07:00:58'),
(15, 'Roma', 5, 3, 18500000000.00, 2024, 3000, 'gasoline', 'automatic', 'Tr???ng Bianco', 'Ferrari Roma grand touring coupe', 'available', '2026-01-02 07:00:58'),
(16, 'Hurac??n EVO', 6, 5, 19800000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh Verde Mantis', 'Lamborghini Hurac??n EVO v???i ?????ng c?? V10 5.2L 640 m?? l???c', 'available', '2026-01-02 07:00:58'),
(17, 'Urus S', 6, 2, 16500000000.00, 2024, 5000, 'gasoline', 'automatic', 'V??ng Giallo', 'Lamborghini Urus S Super SUV 666 m?? l???c', 'available', '2026-01-02 07:00:58'),
(18, 'Revuelto', 6, 5, 45000000000.00, 2025, 0, 'gasoline', 'automatic', 'Cam Arancio', 'Lamborghini Revuelto V12 hybrid m???i nh???t', 'available', '2026-01-02 07:00:58'),
(19, '720S Spider', 7, 5, 23500000000.00, 2024, 0, 'gasoline', 'automatic', 'Cam McLaren', 'McLaren 720S Spider v???i ?????ng c?? V8 4.0L twin-turbo 720 m?? l???c', 'available', '2026-01-02 07:00:58'),
(20, 'Artura', 7, 5, 16800000000.00, 2024, 2000, 'gasoline', 'automatic', 'Xanh Ludus', 'McLaren Artura hybrid supercar', 'available', '2026-01-02 07:00:58'),
(21, 'Continental GT', 8, 3, 18900000000.00, 2025, 0, 'gasoline', 'automatic', 'Xanh Verdant', 'Bentley Continental GT Speed v???i ?????ng c?? W12 659 m?? l???c', 'available', '2026-01-02 07:00:58'),
(22, 'Flying Spur', 8, 1, 16500000000.00, 2024, 0, 'gasoline', 'automatic', '??en Onyx', 'Bentley Flying Spur V8 luxury sedan', 'available', '2026-01-02 07:00:58'),
(23, 'Bentayga', 8, 2, 15200000000.00, 2024, 8000, 'gasoline', 'automatic', 'Tr???ng Ghost', 'Bentley Bentayga S luxury SUV', 'available', '2026-01-02 07:00:58'),
(24, 'Phantom', 9, 1, 55000000000.00, 2024, 0, 'gasoline', 'automatic', '??en Diamond', 'Rolls-Royce Phantom Extended flagship sedan', 'available', '2026-01-02 07:00:58'),
(25, 'Cullinan', 9, 2, 42000000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh Peacock', 'Rolls-Royce Cullinan Black Badge SUV', 'available', '2026-01-02 07:00:58'),
(26, 'Ghost', 9, 1, 35000000000.00, 2024, 5000, 'gasoline', 'automatic', 'B???c Silver', 'Rolls-Royce Ghost luxury sedan', 'available', '2026-01-02 07:00:58'),
(27, 'MC20', 10, 5, 15800000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh Blu Infinito', 'Maserati MC20 si??u xe v???i ?????ng c?? V6 Nettuno 630 m?? l???c', 'available', '2026-01-02 07:00:58'),
(28, 'GranTurismo', 10, 3, 12500000000.00, 2025, 0, 'gasoline', 'automatic', '????? Rosso', 'Maserati GranTurismo Trofeo grand touring', 'available', '2026-01-02 07:00:58'),
(29, 'LC 500', 11, 3, 8500000000.00, 2024, 0, 'gasoline', 'automatic', 'V??ng Flare', 'Lexus LC 500 coupe v???i ?????ng c?? V8 5.0L 471 m?? l???c', 'available', '2026-01-02 07:00:58'),
(30, 'LX 600', 11, 2, 9200000000.00, 2024, 10000, 'gasoline', 'automatic', 'Tr???ng Sonic', 'Lexus LX 600 F Sport flagship SUV', 'available', '2026-01-02 07:00:58'),
(31, 'Camry 2.5Q', 12, 1, 1420000000.00, 2024, 0, 'gasoline', 'automatic', 'Tr???ng Ng???c Trai', 'Toyota Camry 2.5Q sedan cao c???p', 'available', '2026-01-02 07:00:58'),
(32, 'Land Cruiser', 12, 2, 4090000000.00, 2024, 0, 'gasoline', 'automatic', '??en', 'Toyota Land Cruiser 300 VXR flagship SUV', 'available', '2026-01-02 07:00:58'),
(33, 'Corolla Cross HEV', 12, 7, 920000000.00, 2024, 5000, 'gasoline', 'automatic', 'X??m', 'Toyota Corolla Cross Hybrid crossover', 'available', '2026-01-02 07:00:58');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `car_images`
--

CREATE TABLE `car_images` (
  `id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `image_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `car_images`
--

INSERT INTO `car_images` (`id`, `car_id`, `image_url`) VALUES
(1, 1, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(2, 2, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(3, 3, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(4, 4, 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800'),
(5, 5, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(6, 6, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(7, 7, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(8, 8, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(9, 9, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(10, 10, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(11, 11, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(12, 12, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(13, 13, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(14, 14, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(15, 15, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(16, 16, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(17, 17, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(18, 18, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(19, 19, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(20, 20, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(21, 21, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(22, 22, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(23, 23, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(24, 24, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(25, 25, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(26, 26, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(27, 27, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(28, 28, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(29, 29, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(30, 30, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(31, 31, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(32, 32, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(33, 33, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `categories`
--

INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Sedan', 'Xe sedan 4 c???a'),
(2, 'SUV', 'Sport Utility Vehicle'),
(3, 'Coupe', 'Xe th??? thao 2 c???a'),
(4, 'Convertible', 'Xe mui tr???n'),
(5, 'Supercar', 'Si??u xe'),
(6, 'Hatchback', 'Xe 5 c???a compact'),
(7, 'Crossover', 'Xe g???m cao ??a d???ng');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','processed') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `favorites`
--

CREATE TABLE `favorites` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `payment_method` enum('bank_transfer','cash') NOT NULL,
  `status` enum('pending','confirmed','cancelled') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(255) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `role`, `status`, `created_at`) VALUES
(1, 'khanhdang', 'khanhdang2440@gmail.com', '$2y$10$G8IdZF3lcO0fpEjTjLxRSOpMxySQGNMgQTgPUxMJUYl5zhp9wrUgq', 'L?? Kh??nh ????ng', '0868065672', NULL, 'user', 1, '2025-12-30 07:21:32');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `cars`
--
ALTER TABLE `cars`
  ADD PRIMARY KEY (`id`),
  ADD KEY `brand_id` (`brand_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Chỉ mục cho bảng `car_images`
--
ALTER TABLE `car_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `car_id` (`car_id`);

--
-- Chỉ mục cho bảng `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `contacts`
--
ALTER TABLE `contacts`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Chỉ mục cho bảng `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`car_id`),
  ADD KEY `car_id` (`car_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `cars`
--
ALTER TABLE `cars`
  ADD CONSTRAINT `cars_ibfk_1` FOREIGN KEY (`brand_id`) REFERENCES `brands` (`id`),
  ADD CONSTRAINT `cars_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Các ràng buộc cho bảng `car_images`
--
ALTER TABLE `car_images`
  ADD CONSTRAINT `car_images_ibfk_1` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`);

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
