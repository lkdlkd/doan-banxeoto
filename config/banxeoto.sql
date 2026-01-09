-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th1 09, 2026 lúc 02:05 AM
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
-- Cấu trúc bảng cho bảng `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `appointment_date` date NOT NULL,
  `appointment_time` time NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(150) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `appointments`
--

INSERT INTO `appointments` (`id`, `user_id`, `car_id`, `appointment_date`, `appointment_time`, `full_name`, `phone`, `email`, `notes`, `status`, `created_at`) VALUES
(0, 3, 107, '2026-01-10', '09:00:00', 'Nguyễn Tấn Lợi', '0868065672', 'ngtanloi1709@gmail.com', '', 'completed', '2026-01-09 00:36:42'),
(0, 3, 107, '2026-01-10', '17:00:00', 'Lê Khánh Đăng', '0868065672', '', '', 'pending', '2026-01-09 00:48:36');

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
(1, 'Mercedes-Benz', 'https://www.carlogos.org/car-logos/mercedes-benz-logo.png', 'Thương hiệu xe sang Đức', '2026-01-02 07:00:58'),
(2, 'BMW', 'https://www.carlogos.org/car-logos/bmw-logo.png', 'Bayerische Motoren Werke AG', '2026-01-02 07:00:58'),
(3, 'Audi', 'https://www.carlogos.org/car-logos/audi-logo.png', 'Thương hiệu xe sang Đức', '2026-01-02 07:00:58'),
(4, 'Porsche', 'https://www.carlogos.org/car-logos/porsche-logo.png', 'Thương hiệu xe thể thao cao cấp Đức', '2026-01-02 07:00:58'),
(5, 'Ferrari', 'https://www.carlogos.org/car-logos/ferrari-logo.png', 'Hãng siêu xe Italia', '2026-01-02 07:00:58'),
(6, 'Lamborghini', 'https://www.carlogos.org/car-logos/lamborghini-logo.png', 'Hãng siêu xe Italia', '2026-01-02 07:00:58'),
(7, 'McLaren', 'https://www.carlogos.org/car-logos/mclaren-logo.png', 'Hãng siêu xe Anh Quốc', '2026-01-02 07:00:58'),
(8, 'Bentley', 'https://www.carlogos.org/car-logos/bentley-logo.png', 'Xe siêu sang Anh Quốc', '2026-01-02 07:00:58'),
(9, 'Rolls-Royce', 'https://www.carlogos.org/car-logos/rolls-royce-logo.png', 'Xe siêu sang Anh Quốc', '2026-01-02 07:00:58'),
(10, 'Maserati', 'https://www.carlogos.org/car-logos/maserati-logo.png', 'Xe sang thể thao Italia', '2026-01-02 07:00:58'),
(11, 'Lexus', 'https://www.carlogos.org/car-logos/lexus-logo.png', 'Thương hiệu xe sang Nhật Bản', '2026-01-02 07:00:58'),
(12, 'Toyota', 'https://www.carlogos.org/car-logos/toyota-logo.png', 'Thương hiệu xe Nhật Bản hàng đầu', '2026-01-02 07:00:58'),
(13, 'Hyundai', 'https://hyundaiankhanh.vn/wp-content/uploads/2022/09/y-nghia-logo-hyundai.jpg', 'Hãng xe Nhật', '2026-01-08 13:36:14'),
(14, 'Ford', 'https://inkythuatso.com/uploads/images/2021/11/logo-ford-inkythuatso-01-15-10-52-49.jpg', 'Thương hiệu xe đến từ Hoa Kỳ (USA)', '2026-01-08 14:15:14');

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
  `stock` int(11) DEFAULT 1,
  `engine` varchar(100) DEFAULT NULL,
  `horsepower` int(11) DEFAULT NULL,
  `torque` int(11) DEFAULT NULL,
  `acceleration` decimal(4,1) DEFAULT NULL,
  `drivetrain` varchar(20) DEFAULT NULL,
  `seats` int(11) DEFAULT NULL,
  `doors` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `cars`
--

INSERT INTO `cars` (`id`, `name`, `brand_id`, `category_id`, `price`, `year`, `mileage`, `fuel`, `transmission`, `color`, `description`, `status`, `stock`, `engine`, `horsepower`, `torque`, `acceleration`, `drivetrain`, `seats`, `doors`, `created_at`) VALUES
(34, 'Toyota Vios', 12, 8, 500000000.00, 2025, 0, 'gasoline', 'manual', 'Đen, Kem, Đỏ, Trắng', 'Xe Sedan 4 cửa, thiết kế thân thấp, khoang hành khách và khoang hành lý tách biệt. Vận hành êm ái, tiết kiệm nhiên liệu, phù hợp đi phố và đường dài.', 'available', 10, '1.5L DOHC', 180, 250, 11.0, 'FWD', 5, 4, '2026-01-08 13:40:39'),
(35, 'Hyundai i10', 13, 9, 455000000.00, 2025, 0, 'gasoline', 'manual', 'Đen, Đỏ, Xanh, Trắng', 'Hyundai i10 là mẫu hatchback cỡ nhỏ, thiết kế trẻ trung, dễ lái, tiết kiệm nhiên liệu, phù hợp di chuyển nội đô và người mua xe lần đầu.', 'available', 10, '1.2L MPI', 83, 114, 12.0, 'FWD', 5, 5, '2026-01-08 13:47:28'),
(36, 'Mercedes-Benz GLC 2024.', 1, 10, 2300000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng', 'Mercedes-Benz GLC 2024 là mẫu SUV hạng sang cỡ trung, thiết kế mạnh mẽ, gầm cao, không gian rộng rãi, phù hợp cả di chuyển đô thị lẫn đường dài.', 'available', 10, '2.0L Turbo', 258, 400, 6.2, 'AWD', 5, 5, '2026-01-08 13:59:42'),
(37, 'Hyundai Creta 2024', 13, 11, 799000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Xanh, Đen', 'Hyundai Creta 2024 là mẫu CUV đô thị gầm cao, thiết kế hiện đại, trang bị đa dạng, phù hợp cả đi phố và chạy đường dài cho gia đình nhỏ.', 'available', 10, '1.5L Turbo', 160, 253, 12.0, 'FWD', 5, 5, '2026-01-08 14:04:12'),
(38, 'Toyota Veloz Cross 2024', 12, 12, 860000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Đỏ, Trắng', 'Toyota Veloz Cross 2024 là mẫu MPV đa dụng lai CUV với thiết kế gầm cao hiện đại, không gian rộng rãi 7 chỗ, phù hợp gia đình và di chuyển linh hoạt.', 'available', 10, '1.5L Dual VVT-i', 103, 137, 13.0, 'FWD', 7, 5, '2026-01-08 14:08:37'),
(39, 'Mercedes-Benz C Coupe', 1, 13, 2200000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng', 'Mercedes-Benz C Coupe là mẫu coupe hạng sang 2 cửa, thiết kế thể thao, thanh lịch, hướng đến trải nghiệm lái và phong cách cá nhân.', 'available', 10, '2.0L Turbo', 258, 370, 7.5, 'RWD', 4, 2, '2026-01-08 14:11:35'),
(40, 'Ford Ranger 2024', 14, 14, 965000000.00, 2024, 0, 'diesel', 'manual', 'Vàng, Đen', 'Ford Ranger 2024 là mẫu bán tải mạnh mẽ, bền bỉ, đa dụng, phù hợp vừa chở hàng vừa sử dụng gia đình và đi địa hình.', 'available', 10, '2.0L Turbo Diesel', 210, 500, 10.0, 'RWD', 5, 4, '2026-01-08 14:16:44'),
(41, 'Lamborghini Aventador', 6, 17, 45000000000.00, 2023, 0, 'gasoline', 'automatic', 'Đen', 'Lamborghini Aventador là siêu xe biểu tượng với động cơ V12 hút khí tự nhiên, thiết kế cực đoan và hiệu năng đỉnh cao.', 'available', 2, '6.5L V12 hút khí tự nhiên', 700, 729, 2.9, 'AWD', 2, NULL, '2026-01-08 14:20:58'),
(42, 'BMW 1 Series', 2, 9, 1900000000.00, 2023, 0, 'gasoline', 'automatic', 'Xanh, Trắng', 'BMW 1 Series là mẫu hatchback hạng sang cỡ nhỏ, thiết kế thể thao, cảm giác lái đặc trưng BMW, phù hợp di chuyển đô thị và người trẻ yêu thích thương hiệu cao cấp.', 'available', 10, '1.5L Turbo (118i)', 178, 220, 7.1, 'FWD', 5, 5, '2026-01-08 14:28:38'),
(43, 'LaFerrari', 5, 17, 70000000000.00, 2017, 0, 'gasoline', 'automatic', 'Đỏ', 'aFerrari là hypercar biểu tượng của Ferrari, kết hợp động cơ V12 và công nghệ hybrid HY-KERS, đại diện cho đỉnh cao công nghệ và hiệu năng của hãng.', 'available', 3, '6.3L V12 hút khí tự nhiên + mô-tơ điện (Hybrid HY-KERS)', 963, 900, 6.0, 'RWD', 2, 2, '2026-01-08 14:35:03'),
(44, 'Bentley Continental GT', 8, 13, 23000000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám', 'Bentley Continental GT là mẫu coupe hạng sang hiệu suất cao, kết hợp hoàn hảo giữa sự sang trọng thủ công Anh quốc và sức mạnh động cơ mạnh mẽ.', 'available', 5, '6.0L W12 Twin-Turbo', 626, 700, 4.0, 'AWD', 4, 2, '2026-01-08 14:38:43'),
(45, 'Audi A4', 3, 8, 2100000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đỏ', 'Audi A4 là mẫu sedan hạng sang cỡ trung với thiết kế tinh tế, công nghệ hiện đại và khả năng vận hành cân bằng, phù hợp doanh nhân và gia đình nhỏ.', 'available', 10, '2.0L TFSI Turbo', 190, 320, 8.3, 'FWD', 5, 4, '2026-01-08 14:43:37'),
(46, 'Lexus ES', 11, 8, 3200000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám', 'Lexus ES là mẫu sedan hạng sang cỡ trung, nổi bật với sự êm ái, độ bền cao, nội thất tinh tế và trải nghiệm lái thoải mái hàng đầu phân khúc.', 'available', 10, '2.5L Hybrid (ES 300h)', 215, 243, 8.1, 'FWD', 5, 4, '2026-01-08 14:46:48'),
(47, 'Maserati MC20', 10, 17, 25000000000.00, 2023, 0, 'gasoline', 'automatic', 'Trắng', 'Maserati MC20 là siêu xe hiệu suất cao mang DNA đua xe, trang bị động cơ Nettuno V6 mới nhất của Maserati, thiết kế khí động học tối ưu và hiệu năng vượt trội.', 'available', 3, '3.0L V6 Twin-Turbo “Nettuno”', 621, 730, 3.0, 'RWD', 2, 2, '2026-01-08 14:52:23'),
(48, 'Porsche 911', 4, 16, 15000000000.00, 2024, 0, 'gasoline', 'manual', 'Vàng, Trắng', 'Porsche 911 là mẫu xe thể thao biểu tượng với thiết kế đặc trưng, động cơ đặt sau và khả năng vận hành chính xác, cân bằng hàng đầu thế giới.', 'available', 3, '3.0L Boxer 6 Twin-Turbo (Carrera, Carrera S, GTS)', 385, 450, 4.2, 'RWD', 4, 2, '2026-01-08 14:56:22'),
(49, 'BMW X5', 2, 10, 4500000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'BMW X5 là mẫu SUV hạng sang cỡ lớn với thiết kế thể thao, không gian rộng rãi, công nghệ hiện đại và khả năng vận hành đỉnh cao.', 'available', 10, '3.0L Inline-6 Turbo', 335, 450, 5.5, 'AWD', 5, 5, '2026-01-09 08:00:00'),
(50, 'Toyota Camry', 12, 8, 1200000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Đỏ', 'Toyota Camry là mẫu sedan hạng trung cao cấp, thiết kế sang trọng, vận hành êm ái, tiết kiệm nhiên liệu, phù hợp gia đình và doanh nhân.', 'available', 10, '2.5L Hybrid', 218, 221, 8.5, 'FWD', 5, 4, '2026-01-09 08:05:00'),
(51, 'Mercedes-Benz S-Class', 1, 8, 6000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng', 'Mercedes-Benz S-Class là đỉnh cao của dòng sedan hạng sang, với công nghệ tiên tiến nhất, nội thất xa hoa và trải nghiệm lái đẳng cấp.', 'available', 10, '3.0L Inline-6 Turbo', 429, 520, 4.9, 'RWD', 5, 4, '2026-01-09 08:10:00'),
(52, 'Audi Q7', 3, 10, 3800000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám, Đen, Trắng', 'Audi Q7 là mẫu SUV hạng sang 7 chỗ, kết hợp không gian rộng rãi, công nghệ Quattro và thiết kế tinh tế, phù hợp gia đình lớn.', 'available', 10, '3.0L V6 TFSI', 335, 500, 5.9, 'AWD', 7, 5, '2026-01-09 08:15:00'),
(53, 'Hyundai Tucson', 13, 11, 950000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng, Xanh', 'Hyundai Tucson là mẫu crossover cỡ trung với thiết kế hiện đại, trang bị an toàn đầy đủ, phù hợp cho gia đình trẻ.', 'available', 10, '2.0L Turbo', 180, 265, 9.5, 'FWD', 5, 5, '2026-01-09 08:20:00'),
(54, 'Toyota Fortuner', 12, 10, 1400000000.00, 2024, 0, 'diesel', 'automatic', 'Đen, Trắng, Bạc', 'Toyota Fortuner là mẫu SUV 7 chỗ địa hình, bền bỉ, mạnh mẽ, phù hợp đi đường trường và địa hình khó.', 'available', 10, '2.8L Turbo Diesel', 201, 500, 10.0, 'RWD', 7, 5, '2026-01-09 08:25:00'),
(55, 'Porsche Cayenne', 4, 10, 5500000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh, Đen, Trắng', 'Porsche Cayenne là mẫu SUV thể thao hạng sang với hiệu năng vượt trội, thiết kế đẳng cấp và trải nghiệm lái Porsche đích thực.', 'available', 10, '3.0L V6 Turbo', 335, 450, 6.2, 'AWD', 5, 5, '2026-01-09 08:30:00'),
(56, 'Ferrari 488 GTB', 5, 16, 35000000000.00, 2023, 0, 'gasoline', 'automatic', 'Đỏ, Vàng', 'Ferrari 488 GTB là mẫu xe thể thao siêu sang với động cơ V8 twin-turbo mạnh mẽ, thiết kế khí động học hoàn hảo.', 'available', 10, '3.9L V8 Twin-Turbo', 661, 760, 3.0, 'RWD', 2, 2, '2026-01-09 08:35:00'),
(57, 'BMW 3 Series', 2, 8, 2400000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Xanh, Đen', 'BMW 3 Series là mẫu sedan thể thao hạng trung cao cấp, cảm giác lái đặc trưng BMW, thiết kế sang trọng và công nghệ hiện đại.', 'available', 10, '2.0L Turbo', 255, 400, 5.8, 'RWD', 5, 4, '2026-01-09 08:40:00'),
(58, 'Lexus RX', 11, 10, 3500000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Xám', 'Lexus RX là mẫu SUV hạng sang cỡ trung với độ tin cậy cao, nội thất sang trọng, vận hành êm ái và tiết kiệm nhiên liệu.', 'available', 10, '2.5L Hybrid', 245, 270, 8.0, 'AWD', 5, 5, '2026-01-09 08:45:00'),
(59, 'Audi A6', 3, 8, 2800000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám, Đen, Trắng', 'Audi A6 là mẫu sedan hạng sang cỡ trung với công nghệ Quattro, thiết kế tinh tế, phù hợp cho doanh nhân và gia đình.', 'available', 10, '2.0L TFSI', 201, 320, 7.9, 'AWD', 5, 4, '2026-01-09 08:50:00'),
(60, 'Mercedes-Benz E-Class', 1, 8, 3200000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Bạc', 'Mercedes-Benz E-Class là mẫu sedan hạng sang với sự cân bằng hoàn hảo giữa sang trọng, công nghệ và hiệu năng.', 'available', 10, '2.0L Turbo', 255, 370, 6.3, 'RWD', 5, 4, '2026-01-09 08:55:00'),
(61, 'Lamborghini Huracan', 6, 17, 38000000000.00, 2024, 0, 'gasoline', 'automatic', 'Vàng, Xanh, Cam', 'Lamborghini Huracan là siêu xe với động cơ V10 mạnh mẽ, thiết kế sắc sảo và hiệu năng tuyệt vời cho trải nghiệm lái đỉnh cao.', 'available', 10, '5.2L V10', 631, 600, 2.9, 'AWD', 2, 2, '2026-01-09 09:00:00'),
(62, 'Toyota Corolla', 12, 8, 750000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Bạc', 'Toyota Corolla là mẫu sedan cỡ B bán chạy nhất thế giới, tiết kiệm nhiên liệu, bền bỉ, phù hợp cho gia đình và người mua xe lần đầu.', 'available', 10, '1.8L Hybrid', 140, 170, 10.5, 'FWD', 5, 4, '2026-01-09 09:05:00'),
(63, 'Hyundai Sonata', 13, 8, 1050000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Xám', 'Hyundai Sonata là mẫu sedan hạng D với thiết kế hiện đại, trang bị công nghệ đầy đủ, không gian rộng rãi và giá cả hợp lý.', 'available', 10, '2.5L Smartstream', 191, 245, 8.4, 'FWD', 5, 4, '2026-01-09 09:10:00'),
(64, 'Ford Everest', 14, 10, 1350000000.00, 2024, 0, 'diesel', 'automatic', 'Đen, Trắng, Nâu', 'Ford Everest là mẫu SUV 7 chỗ cao cấp với khả năng vận hành mạnh mẽ, không gian rộng rãi, phù hợp cho gia đình và đi đường dài.', 'available', 10, '2.0L Bi-Turbo Diesel', 210, 500, 9.8, 'RWD', 7, 5, '2026-01-09 09:15:00'),
(65, 'BMW 7 Series', 2, 8, 5500000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xanh', 'BMW 7 Series là sedan hạng sang cao cấp nhất của BMW, với công nghệ tiên tiến, nội thất xa hoa và hiệu năng vượt trội.', 'available', 10, '3.0L Inline-6 Turbo', 375, 520, 5.1, 'RWD', 5, 4, '2026-01-09 09:20:00'),
(66, 'Audi Q5', 3, 11, 2700000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Xám', 'Audi Q5 là mẫu crossover hạng sang cỡ trung với thiết kế cân đối, công nghệ Quattro và không gian tiện nghi.', 'available', 10, '2.0L TFSI', 248, 370, 6.3, 'AWD', 5, 5, '2026-01-09 09:25:00'),
(67, 'Lexus NX', 11, 11, 2600000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng, Đen', 'Lexus NX là mẫu crossover hạng sang cỡ nhỏ với thiết kế thể thao, nội thất sang trọng và độ tin cậy cao của Lexus.', 'available', 10, '2.5L Hybrid', 239, 250, 7.7, 'AWD', 5, 5, '2026-01-09 09:30:00'),
(68, 'Mercedes-Benz GLE', 1, 10, 4800000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Bạc', 'Mercedes-Benz GLE là mẫu SUV hạng sang cỡ lớn với không gian rộng rãi, công nghệ hiện đại và khả năng vận hành đa địa hình.', 'available', 10, '3.0L Inline-6 Turbo', 362, 500, 5.7, 'AWD', 5, 5, '2026-01-09 09:35:00'),
(69, 'Porsche Panamera', 4, 8, 7000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'Porsche Panamera là sedan thể thao hạng sang 4 cửa, kết hợp sự thoải mái của sedan với hiệu năng Porsche đặc trưng.', 'available', 10, '2.9L V6 Twin-Turbo', 434, 550, 4.4, 'RWD', 4, 4, '2026-01-09 09:40:00'),
(70, 'Ferrari Roma', 5, 13, 28000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Xanh, Bạc', 'Ferrari Roma là mẫu coupe hạng sang với thiết kế lấy cảm hứng từ Rome thập niên 50-60, kết hợp sang trọng và hiệu năng.', 'available', 10, '3.9L V8 Twin-Turbo', 611, 760, 3.4, 'RWD', 4, 2, '2026-01-09 09:45:00'),
(71, 'McLaren 720S', 7, 17, 32000000000.00, 2023, 0, 'gasoline', 'automatic', 'Cam, Đen, Xanh', 'McLaren 720S là siêu xe với công nghệ F1, thiết kế khí động học hoàn hảo và hiệu năng vượt trội mang đến trải nghiệm lái không thể quên.', 'available', 10, '4.0L V8 Twin-Turbo', 710, 770, 2.9, 'RWD', 2, 2, '2026-01-09 09:50:00'),
(72, 'Rolls-Royce Ghost', 9, 8, 35000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xanh', 'Rolls-Royce Ghost là đỉnh cao của sedan siêu sang, với nội thất thủ công tinh xảo, vận hành êm ái tuyệt đối và đẳng cấp vô song.', 'available', 10, '6.75L V12 Twin-Turbo', 563, 850, 4.8, 'RWD', 5, 4, '2026-01-09 09:55:00'),
(73, 'Bentley Bentayga', 8, 10, 19000000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh, Đen, Trắng', 'Bentley Bentayga là mẫu SUV siêu sang với nội thất thủ công cao cấp, động cơ mạnh mẽ và khả năng vận hành đa địa hình.', 'available', 10, '4.0L V8 Twin-Turbo', 542, 770, 4.5, 'AWD', 5, 5, '2026-01-09 10:00:00'),
(74, 'Maserati Ghibli', 10, 8, 4200000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh, Đen, Trắng', 'Maserati Ghibli là sedan thể thao hạng sang của Ý với âm thanh động cơ đặc trưng, thiết kế quyến rũ và vận hành mạnh mẽ.', 'available', 10, '3.0L V6 Twin-Turbo', 345, 500, 5.5, 'RWD', 5, 4, '2026-01-09 10:05:00'),
(75, 'Toyota Land Cruiser', 12, 10, 5500000000.00, 2024, 0, 'diesel', 'automatic', 'Trắng, Đen, Bạc', 'Toyota Land Cruiser là mẫu SUV huyền thoại với độ bền bỉ cao nhất, khả năng địa hình mạnh mẽ, phù hợp cho mọi điều kiện.', 'available', 10, '3.3L V6 Turbo Diesel', 304, 700, 7.0, 'AWD', 7, 5, '2026-01-09 10:10:00'),
(76, 'Hyundai Palisade', 13, 10, 1550000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'Hyundai Palisade là mẫu SUV 8 chỗ cao cấp với thiết kế sang trọng, không gian rộng rãi và trang bị công nghệ đầy đủ.', 'available', 10, '3.8L V6', 295, 355, 8.1, 'AWD', 8, 5, '2026-01-09 10:15:00'),
(77, 'Ford Mustang', 14, 16, 3500000000.00, 2024, 0, 'gasoline', 'manual', 'Đỏ, Vàng, Đen', 'Ford Mustang là biểu tượng xe thể thao Mỹ với động cơ V8 mạnh mẽ, âm thanh uy lực và thiết kế cơ bắp đặc trưng.', 'available', 10, '5.0L V8', 450, 529, 4.3, 'RWD', 4, 2, '2026-01-09 10:20:00'),
(78, 'Audi A8', 3, 8, 5800000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'Audi A8 là sedan hạng sang cao cấp nhất của Audi với công nghệ tiên tiến, nội thất sang trọng và hiệu năng mạnh mẽ.', 'available', 10, '3.0L V6 TFSI', 335, 500, 5.6, 'AWD', 5, 4, '2026-01-09 10:25:00'),
(79, 'BMW X3', 2, 11, 2900000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh, Đen, Trắng', 'BMW X3 là mẫu crossover hạng sang cỡ trung với thiết kế thể thao, vận hành linh hoạt và công nghệ BMW hiện đại.', 'available', 10, '2.0L Turbo', 248, 350, 6.5, 'AWD', 5, 5, '2026-01-09 10:30:00'),
(80, 'Mercedes-Benz A-Class', 1, 9, 1650000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng, Đen', 'Mercedes-Benz A-Class là hatchback hạng sang cỡ nhỏ với thiết kế trẻ trung, công nghệ MBUX và chất lượng Mercedes đặc trưng.', 'available', 10, '1.3L Turbo', 163, 250, 8.7, 'FWD', 5, 5, '2026-01-09 10:35:00'),
(81, 'Toyota RAV4', 12, 11, 1150000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám, Trắng, Đen', 'Toyota RAV4 là crossover bán chạy nhất thế giới với thiết kế mạnh mẽ, không gian rộng rãi và độ tin cậy cao.', 'available', 10, '2.5L Hybrid', 219, 221, 8.2, 'AWD', 5, 5, '2026-01-09 10:40:00'),
(82, 'Audi Q3', 3, 11, 2200000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Xanh, Đen', 'Audi Q3 là crossover hạng sang cỡ nhỏ với thiết kế hiện đại, công nghệ Quattro và khoang lái cao cấp.', 'available', 10, '2.0L TFSI', 227, 350, 7.4, 'AWD', 5, 5, '2026-01-09 10:45:00'),
(83, 'Lexus LS', 11, 8, 8500000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xanh', 'Lexus LS là sedan hạng sang đầu bảng của Lexus với nội thất xa hoa, vận hành êm ái tuyệt đối và công nghệ tiên tiến.', 'available', 10, '3.5L V6 Hybrid', 354, 350, 5.4, 'RWD', 5, 4, '2026-01-09 10:50:00'),
(84, 'Hyundai Santa Fe', 13, 10, 1350000000.00, 2024, 0, 'gasoline', 'automatic', 'Nâu, Đen, Trắng', 'Hyundai Santa Fe là SUV 7 chỗ cao cấp với thiết kế sang trọng, trang bị đầy đủ và giá trị sử dụng cao.', 'available', 10, '2.5L Turbo', 277, 421, 7.8, 'AWD', 7, 5, '2026-01-09 10:55:00'),
(85, 'BMW M4', 2, 16, 6500000000.00, 2024, 0, 'gasoline', 'manual', 'Xanh, Đen, Trắng', 'BMW M4 là coupe thể thao hiệu suất cao với động cơ mạnh mẽ, thiết kế thể thao và khả năng vận hành đỉnh cao.', 'available', 10, '3.0L Inline-6 Twin-Turbo', 503, 650, 3.9, 'RWD', 4, 2, '2026-01-09 11:00:00'),
(86, 'Mercedes-Benz G-Class', 1, 10, 12000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'Mercedes-Benz G-Class là SUV huyền thoại với thiết kế vuông vức đặc trưng, khả năng off-road vượt trội và sang trọng tuyệt đối.', 'available', 10, '4.0L V8 Twin-Turbo', 421, 610, 5.6, 'AWD', 5, 5, '2026-01-09 11:05:00'),
(87, 'Toyota Yaris', 12, 9, 550000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng, Xanh', 'Toyota Yaris là hatchback cỡ nhỏ tiết kiệm nhiên liệu, dễ lái, phù hợp di chuyển đô thị và người mua xe lần đầu.', 'available', 10, '1.5L Dual VVT-i', 107, 140, 11.2, 'FWD', 5, 5, '2026-01-09 11:10:00'),
(88, 'Porsche Macan', 4, 11, 3800000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Xanh, Trắng', 'Porsche Macan là crossover thể thao hạng sang với DNA Porsche, vận hành sắc bén và thiết kế đẳng cấp.', 'available', 10, '2.0L Turbo', 261, 400, 6.7, 'AWD', 5, 5, '2026-01-09 11:15:00'),
(89, 'Ferrari F8 Tributo', 5, 16, 42000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Vàng, Trắng', 'Ferrari F8 Tributo là siêu xe kế thừa 488 GTB với động cơ V8 mạnh mẽ nhất, thiết kế khí động học và hiệu năng đỉnh cao.', 'available', 10, '3.9L V8 Twin-Turbo', 710, 770, 2.9, 'RWD', 2, 2, '2026-01-09 11:20:00'),
(90, 'Audi RS6 Avant', 3, 10, 8200000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám, Đen, Xanh', 'Audi RS6 Avant là wagon hiệu suất cao với động cơ V8 twin-turbo, thiết kế thể thao và khả năng thực dụng tuyệt vời.', 'available', 10, '4.0L V8 Twin-Turbo', 591, 800, 3.6, 'AWD', 5, 5, '2026-01-09 11:25:00'),
(91, 'BMW X7', 2, 10, 6500000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Xanh', 'BMW X7 là SUV hạng sang cỡ lớn 7 chỗ với không gian rộng rãi nhất, sang trọng và công nghệ BMW hiện đại.', 'available', 10, '3.0L Inline-6 Turbo', 375, 520, 5.8, 'AWD', 7, 5, '2026-01-09 11:30:00'),
(92, 'Lexus LX', 11, 10, 9500000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Bạc', 'Lexus LX là SUV cao cấp nhất của Lexus với khả năng off-road mạnh mẽ, nội thất xa hoa và độ tin cậy tuyệt đối.', 'available', 10, '3.5L V6 Twin-Turbo', 409, 650, 6.9, 'AWD', 7, 5, '2026-01-09 11:35:00'),
(93, 'Mercedes-Benz CLA', 1, 13, 1950000000.00, 2024, 0, 'gasoline', 'automatic', 'Trắng, Đen, Xanh', 'Mercedes-Benz CLA là coupe 4 cửa với thiết kế thể thao quyến rũ, công nghệ hiện đại và vận hành năng động.', 'available', 10, '2.0L Turbo', 221, 350, 7.1, 'FWD', 5, 4, '2026-01-09 11:40:00'),
(94, 'Hyundai Kona', 13, 11, 750000000.00, 2024, 0, 'gasoline', 'automatic', 'Cam, Trắng, Đen', 'Hyundai Kona là crossover cỡ nhỏ với thiết kế cá tính, năng động, phù hợp cho người trẻ và di chuyển đô thị.', 'available', 10, '2.0L MPI', 147, 179, 10.2, 'FWD', 5, 5, '2026-01-09 11:45:00'),
(95, 'Toyota Alphard', 12, 12, 4200000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Bạc', 'Toyota Alphard là MPV hạng sang với nội thất siêu cao cấp, không gian VIP và sự thoải mái tuyệt đối.', 'available', 10, '2.5L Hybrid', 178, 221, 10.0, 'FWD', 7, 5, '2026-01-09 11:50:00'),
(96, 'Porsche Taycan', 4, 8, 5500000000.00, 2024, 0, 'electric', 'automatic', 'Trắng, Đen, Xanh', 'Porsche Taycan là sedan điện hiệu suất cao đầu tiên của Porsche với gia tốc siêu nhanh và công nghệ tiên tiến.', 'available', 10, 'Electric Dual Motor', 469, 640, 4.0, 'AWD', 4, 4, '2026-01-09 11:55:00'),
(97, 'Lamborghini Urus', 6, 10, 22000000000.00, 2024, 0, 'gasoline', 'automatic', 'Vàng, Đen, Xanh', 'Lamborghini Urus là SUV siêu sang với hiệu năng siêu xe, thiết kế Lamborghini đặc trưng và tính thực dụng cao.', 'available', 10, '4.0L V8 Twin-Turbo', 641, 850, 3.6, 'AWD', 5, 5, '2026-01-09 12:00:00'),
(98, 'BMW 5 Series', 2, 8, 3200000000.00, 2024, 0, 'gasoline', 'automatic', 'Xám, Đen, Trắng', 'BMW 5 Series là sedan hạng sang cỡ trung với sự cân bằng hoàn hảo giữa sang trọng, công nghệ và vận hành thể thao.', 'available', 10, '2.0L Turbo', 248, 350, 6.8, 'RWD', 5, 4, '2026-01-09 12:05:00'),
(99, 'Audi e-tron GT', 3, 16, 7800000000.00, 2024, 0, 'electric', 'automatic', 'Xám, Đen, Xanh', 'Audi e-tron GT là sedan thể thao điện với thiết kế tuyệt đẹp, hiệu năng mạnh mẽ và công nghệ Audi tiên tiến.', 'available', 10, 'Electric Dual Motor', 469, 640, 4.1, 'AWD', 4, 4, '2026-01-09 12:10:00'),
(100, 'Mercedes-Benz GLS', 1, 10, 6800000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xám', 'Mercedes-Benz GLS là SUV hạng sang cỡ lớn nhất với không gian 7 chỗ sang trọng, công nghệ đỉnh cao và sự thoải mái tuyệt vời.', 'available', 10, '3.0L Inline-6 Turbo', 362, 500, 6.2, 'AWD', 7, 5, '2026-01-09 12:15:00'),
(101, 'Toyota Hilux', 12, 14, 850000000.00, 2024, 0, 'diesel', 'manual', 'Trắng, Đen, Xám', 'Toyota Hilux là bán tải huyền thoại với độ bền bỉ cao nhất, mạnh mẽ, tiết kiệm và đa năng cho mọi công việc.', 'available', 10, '2.8L Turbo Diesel', 201, 500, 10.5, 'RWD', 5, 4, '2026-01-09 12:20:00'),
(102, 'Hyundai Venue', 13, 11, 550000000.00, 2024, 0, 'gasoline', 'automatic', 'Đỏ, Trắng, Xanh', 'Hyundai Venue là crossover cỡ nhỏ với thiết kế trẻ trung, tiết kiệm nhiên liệu và giá cả phải chăng.', 'available', 9, '1.0L Turbo', 118, 172, 10.8, 'FWD', 5, 5, '2026-01-09 12:25:00'),
(103, 'Porsche 718 Cayman', 4, 16, 4500000000.00, 2024, 0, 'gasoline', 'manual', 'Vàng, Đỏ, Xanh', 'Porsche 718 Cayman là coupe thể thao 2 chỗ với động cơ đặt giữa, vận hành hoàn hảo và thiết kế Porsche đặc trưng.', 'available', 9, '2.0L Turbo Boxer 4', 296, 380, 5.1, 'RWD', 2, 2, '2026-01-09 12:30:00'),
(104, 'McLaren GT', 7, 13, 18000000000.00, 2024, 0, 'gasoline', 'automatic', 'Cam, Xanh, Trắng', 'McLaren GT là grand tourer với hiệu năng siêu xe, không gian hành lý lớn và sự thoải mái cho chuyến đi dài.', 'available', 10, '4.0L V8 Twin-Turbo', 612, 630, 3.2, 'RWD', 2, 2, '2026-01-09 12:35:00'),
(105, 'Bentley Flying Spur', 8, 8, 18000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Xanh', 'Bentley Flying Spur là sedan siêu sang 4 cửa với nội thất thủ công cao cấp, động cơ mạnh mẽ và sự thoải mái tuyệt đối.', 'available', 9, '6.0L W12 Twin-Turbo', 626, 900, 3.8, 'AWD', 5, 4, '2026-01-09 12:40:00'),
(106, 'Maserati Levante', 10, 10, 5800000000.00, 2024, 0, 'gasoline', 'automatic', 'Xanh, Đen, Trắng', 'Maserati Levante là SUV hạng sang của Ý với âm thanh động cơ đặc trưng, thiết kế quyến rũ và vận hành thể thao.', 'available', 9, '3.0L V6 Twin-Turbo', 345, 500, 6.0, 'AWD', 5, 5, '2026-01-09 12:45:00'),
(107, 'Rolls-Royce Cullinan', 9, 10, 38000000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Trắng, Bạc', 'Rolls-Royce Cullinan là SUV siêu sang đỉnh cao với nội thất xa hoa tuyệt đối, vận hành êm ái và khả năng off-road bất ngờ.', 'available', 9, '6.75L V12 Twin-Turbo', 563, 850, 5.0, 'AWD', 5, 5, '2026-01-09 12:50:00'),
(108, 'Audi Q8', 3, 10, 4800000000.00, 2024, 0, 'gasoline', 'automatic', 'Đen, Xám, Trắng', 'Audi Q8 là SUV coupe hạng sang với thiết kế thể thao, công nghệ Quattro và nội thất cao cấp, phù hợp người yêu thích phong cách.', 'available', 10, '3.0L V6 TFSI', 335, 500, 5.9, 'AWD', 5, 5, '2026-01-09 12:55:00');

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
(36, 35, 'https://hyundaingocan.com/wp-content/uploads/2020/07/Hyundai-i10-2021-m%C3%A0u-xanh.png'),
(37, 35, 'https://hyundaingocan.com/wp-content/uploads/2020/07/Hyundai-i10-2021-m%C3%A0u-%C4%91%E1%BB%8F.png'),
(38, 35, 'https://hyundaingocan.com/wp-content/uploads/2020/07/Hyundai-i10-2021-m%C3%A0u-tr%E1%BA%AFng.png'),
(39, 35, 'https://binhdinhhyundai.com/wp-content/uploads/2021/11/huyndai-grand-i10-hb-240617-c04.jpg'),
(40, 34, 'https://toyota3s-longbien.vn/wp-content/uploads/2022/03/vios-2023-den.png'),
(41, 34, 'https://toyota3s-longbien.vn/wp-content/uploads/2022/03/vios-2023-nau.png'),
(42, 34, 'https://toyota3s-longbien.vn/wp-content/uploads/2022/03/vios-2023-do.png'),
(43, 34, 'https://toyota3s-longbien.vn/wp-content/uploads/2022/03/vios-2023-trang-ngoc-trai.png'),
(44, 36, 'https://file.kelleybluebookimages.com/kbb/base/evox/CP/50785/2024-Mercedes-Benz-GLC-front_50785_032_2400x1800_149.png'),
(45, 36, 'https://images.hgmsites.net/lrg/2024-mercedes-benz-glc-class-glc-300-4matic-coupe-angular-front-exterior-view_100967049_l.webp'),
(46, 37, 'https://hyundaihaiphong.com.vn/wp-content/uploads/2024/08/mau-xe-Hyundai-creta-xanh-600x304.png'),
(47, 37, 'https://hyundaihaiphong.com.vn/wp-content/uploads/2024/08/mau-xe-Hyundai-creta-bac-600x304.png'),
(48, 37, 'https://hyundaihaiphong.com.vn/wp-content/uploads/2024/08/mau-xe-Hyundai-creta-trang-600x304.png'),
(49, 38, 'https://bizweb.dktcdn.net/thumb/large/100/388/878/products/v5.png?v=1666775813723'),
(50, 38, 'https://bizweb.dktcdn.net/thumb/large/100/388/878/products/v1.png?v=1666775813723'),
(51, 38, 'https://bizweb.dktcdn.net/100/388/878/products/v4.png?v=1666775813723'),
(55, 39, 'https://img.pcauto.com/model/images/touPic/my/Mercedes-Benz-AMG-C-Class-Coupe_120.png'),
(56, 40, 'https://ford-suoitien.vn/wp-content/uploads/2021/11/an-toan_Ford_Ranger_2023.jpg'),
(57, 40, 'https://fordlongbien.com/wp-content/uploads/2022/08/ford-ranger-xlt-2023-mau-xam-icon-fordlongbien_com.jpg'),
(60, 41, 'https://png.pngtree.com/png-clipart/20250218/original/pngtree-3d-black-lamborghini-aventado-png-image_20465354.png'),
(62, 42, 'https://images.ctfassets.net/90a0xfzm8yw3/5RZC6RRpBDwhAfGnQfarbo/c8754a30b146b72b92202e5596771b7a/F70_120_M_Sport_Fozon_Portimao_Blue.png?fm=jpg&fl=progressive&q=80'),
(63, 42, 'https://www.lloydmotorgroup.com/ImageLibrary/images/BMW/Retail/Master/New%20Cars/1%20Series/F70%20Cozy%20(800%20x%20400%20px).png?height=359.92766726943944&heightratio=0.5623869801084991&mode=pad&upscale=true&width=640'),
(65, 43, 'https://cafefcdn.com/thumb_w/640/203337114487263232/2021/4/4/photo1617535900726-1617535900884766763217.jpg'),
(66, 43, 'https://hips.hearstapps.com/mtg-prod/65bd42266a15290008875877/ferrari-laferrari-front-view.jpg?format=webp&q=75&w=768&width=768'),
(67, 43, 'https://static0.hotcarsimages.com/wordpress/wp-content/uploads/2022/04/161-Mile-2017-Ferrari-LaFerrari-Aperta-Interior-Via-BringaTrailer.jpeg?dpr=1.5&fit=crop&q=50&w=825'),
(71, 45, 'https://gialanbanh.vn/wp-content/uploads/2024/03/audi-a4-45-tfsi-quattro.jpg'),
(72, 45, 'https://file.kelleybluebookimages.com/kbb/base/evox/CP/53318/2025-Audi-A4-front_53318_032_2400x1800_B1B1.png'),
(74, 46, 'https://lexuscentralsaigon.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/es-250/my22/features/comfort-and-design/es-250-comfort-and-design-bold-look.jpg'),
(76, 46, 'https://lexuscentralsaigon.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/es-250/my22/gallery/exterior/lexus-es250-gallery-ext-09-d.jpg'),
(77, 46, 'https://lexuscentralsaigon.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/es-250/my22/gallery/interior/lexus-es250-gallery-int-02-d.jpg'),
(78, 46, 'https://lexuscentralsaigon.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/es-250/my22/gallery/interior/lexus-es250-gallery-int-05-d.jpg'),
(83, 48, 'https://hstatic.net/379/1000109379/1/2016/8-12/iris__12__master.jpg'),
(84, 48, 'https://images.jdmagicbox.com/quickquotes/images_main/porsche-911-2020-carrera-s-cabriolet-bs6-petrol-white-184476306-7phv4.png'),
(85, 48, 'https://hips.hearstapps.com/hmg-prod/images/2024-porsche-911-st-interior-109-64c9291fda681.jpg?crop=0.669xw%3A1.00xh%3B0.116xw%2C0&resize=1200%3A%2A'),
(88, 47, 'https://maseratihanoi.com/wp-content/uploads/2021/04/Maserati-mc20.jpg'),
(89, 47, 'https://media.gq-magazine.co.uk/photos/5f5a01ffe4b4aa9eec6fcfe2/master/pass/20200910-mc20-06.jpg'),
(90, 47, 'https://media.gq-magazine.co.uk/photos/5f5a01ff020908336ccd4de4/master/w_1600,c_limit/20200910-mc20-04.jpg'),
(91, 47, 'https://media.gq-magazine.co.uk/photos/5f5a01fe42933a7359668a85/master/w_1600,c_limit/20200910-mc20-02.jpg'),
(92, 46, 'https://www.lexus.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/mlp/my22/gallery/interior/lexus-es-gallery-int-07-d.jpg'),
(93, 46, 'https://www.lexus.com.vn/content/dam/lexus-v3-blueprint/models/sedan/es/mlp/my22/gallery/interior/lexus-es-gallery-int-04-d.jpg'),
(95, 46, 'https://images.hgmsites.net/med/2017-lexus-es-es-300h-fwd-rear-exterior-view_100573132_m.jpg'),
(96, 45, 'https://carwow-uk-wp-3.imgix.net/A4-interior-dash.jpg'),
(97, 45, 'https://s3-eu-west-1.amazonaws.com/eurekar-v2/uploads/images/original/2aseats.jpg'),
(99, 45, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTv_edn4asGLIQwkILIqQNMYTB_CWHFxjQkLg&s'),
(101, 44, 'https://car-images.bauersecure.com/wp-images/3424/092-bentley-continental-gt-core-front-silver.jpg'),
(102, 44, 'https://hips.hearstapps.com/autoweek/assets/s3fs-public/new-continental-gt-20.jpg'),
(104, 44, 'https://www.bentleymotors.com/content/dam/bm/websites/bmcom/bentleymotors-com/models/26my/26my-gt/GT%20Base%20rear%20quarter%2016x9.jpg/_jcr_content/renditions/original.image_file.1440.810.file/GT%20Base%20rear%20quarter%2016x9.jpg'),
(105, 43, 'https://i.pinimg.com/736x/de/fe/ce/defece0393dc43e09f2c2eaeea6a5396.jpg'),
(106, 42, 'https://car-images.bauersecure.com/wp-images/3515/1seriesfacelift_54.jpg'),
(111, 41, 'https://thanhnien.mediacdn.vn/uploaded/chicuong/2021_07_08/aventador/204328687_1763959193815239_2444882199303995614_n_CZGX.jpg?width=500'),
(112, 41, 'https://www.carscoops.com/wp-content/uploads/2021/01/Lamborghini-Aventador-Huber-8.jpg'),
(113, 40, 'https://img-ik.cars.co.za/news-site-za/images/2024/03/2500FordRangerPlatinum_135.jpg?tr=w-1200,h-800'),
(114, 40, 'https://vehicle-images.dealerinspire.com/stock-images/chrome/77c48822db73bde760d127d929ef2152.png'),
(117, 39, 'https://static0.carbuzzimages.com/wordpress/wp-content/uploads/gallery-images/original/366000/300/366325.jpg?q=50&fit=contain&w=1150&h=650&dpr=1.5'),
(118, 39, 'https://images.hgmsites.net/med/2023-mercedes-benz-c-class-c-300-coupe-angular-rear-exterior-view_100887307_m.jpg'),
(119, 49, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(120, 49, 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800'),
(121, 49, 'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?w=800'),
(122, 50, 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800'),
(123, 50, 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800'),
(124, 50, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(125, 51, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(126, 51, 'https://images.unsplash.com/photo-1563720360172-67b8f3dce741?w=800'),
(127, 51, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(128, 52, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(129, 52, 'https://images.unsplash.com/photo-1614200187524-dc4b892acf16?w=800'),
(130, 52, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=800'),
(132, 53, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(133, 53, 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800'),
(134, 54, 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800'),
(135, 54, 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800'),
(136, 54, 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800'),
(137, 55, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(138, 55, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(139, 55, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(143, 57, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(144, 57, 'https://images.unsplash.com/photo-1523983388277-336a66bf9bcd?w=800'),
(145, 57, 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800'),
(146, 58, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800'),
(147, 58, 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800'),
(148, 58, 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800'),
(149, 59, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(150, 59, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=800'),
(151, 59, 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800'),
(152, 60, 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800'),
(153, 60, 'https://images.unsplash.com/photo-1616422285623-13ff0162193c?w=800'),
(154, 60, 'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=800'),
(155, 61, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(156, 61, 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=800'),
(157, 61, 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800'),
(158, 62, 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800'),
(159, 62, 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800'),
(160, 62, 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800'),
(162, 63, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(163, 63, 'https://images.unsplash.com/photo-1493238792000-8113da705763?w=800'),
(164, 64, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(165, 64, 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800'),
(166, 64, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(167, 65, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(168, 65, 'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?w=800'),
(169, 65, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(170, 66, 'https://images.unsplash.com/photo-1614200187524-dc4b892acf16?w=800'),
(171, 66, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(172, 66, 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800'),
(173, 67, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800'),
(174, 67, 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800'),
(175, 67, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(176, 68, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(177, 68, 'https://images.unsplash.com/photo-1563720360172-67b8f3dce741?w=800'),
(178, 68, 'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=800'),
(179, 69, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(180, 69, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(181, 69, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(197, 75, 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800'),
(198, 75, 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800'),
(199, 75, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800'),
(201, 76, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(202, 76, 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800'),
(203, 77, 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800'),
(204, 77, 'https://images.unsplash.com/photo-1584345604476-8ec5f82bd3da?w=800'),
(205, 77, 'https://images.unsplash.com/photo-1547744152-14d985cb937f?w=800'),
(206, 78, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(207, 78, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=800'),
(208, 78, 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800'),
(209, 79, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(210, 79, 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800'),
(211, 79, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(212, 80, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(213, 80, 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800'),
(214, 80, 'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=800'),
(215, 81, 'https://images.unsplash.com/photo-1621007947382-bb3c3994e3fb?w=800'),
(216, 81, 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800'),
(217, 81, 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800'),
(218, 82, 'https://images.unsplash.com/photo-1614200187524-dc4b892acf16?w=800'),
(219, 82, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(220, 82, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=800'),
(221, 83, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800'),
(222, 83, 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800'),
(223, 83, 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800'),
(226, 84, 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800'),
(227, 85, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(228, 85, 'https://images.unsplash.com/photo-1523983388277-336a66bf9bcd?w=800'),
(229, 85, 'https://images.unsplash.com/photo-1556189250-72ba954cfc2b?w=800'),
(230, 86, 'https://images.unsplash.com/photo-1520031441872-265e4ff70366?w=800'),
(231, 86, 'https://images.unsplash.com/photo-1563720360172-67b8f3dce741?w=800'),
(232, 86, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(233, 87, 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800'),
(234, 87, 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800'),
(235, 87, 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800'),
(236, 88, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(237, 88, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(238, 88, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(242, 90, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(243, 90, 'https://images.unsplash.com/photo-1614200187524-dc4b892acf16?w=800'),
(244, 90, 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800'),
(245, 91, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(246, 91, 'https://images.unsplash.com/photo-1617814076367-b759c7d7e738?w=800'),
(247, 91, 'https://images.unsplash.com/photo-1494976388531-d1058494cdd8?w=800'),
(248, 92, 'https://images.unsplash.com/photo-1552519507-da3b142c6e3d?w=800'),
(249, 92, 'https://images.unsplash.com/photo-1542362567-b07e54358753?w=800'),
(250, 92, 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800'),
(251, 93, 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800'),
(252, 93, 'https://images.unsplash.com/photo-1616422285623-13ff0162193c?w=800'),
(253, 93, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(254, 94, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(255, 94, 'https://images.unsplash.com/photo-1625231334168-30dc0d7f2607?w=800'),
(256, 94, 'https://images.unsplash.com/photo-1493238792000-8113da705763?w=800'),
(257, 95, 'https://images.unsplash.com/photo-1559416523-140ddc3d238c?w=800'),
(258, 95, 'https://images.unsplash.com/photo-1502877338535-766e1452684a?w=800'),
(259, 95, 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800'),
(260, 96, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(261, 96, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(262, 96, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(263, 97, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(264, 97, 'https://images.unsplash.com/photo-1525609004556-c46c7d6cf023?w=800'),
(265, 97, 'https://images.unsplash.com/photo-1511919884226-fd3cad34687c?w=800'),
(266, 98, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(267, 98, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(268, 98, 'https://images.unsplash.com/photo-1523983388277-336a66bf9bcd?w=800'),
(269, 99, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(270, 99, 'https://images.unsplash.com/photo-1614200187524-dc4b892acf16?w=800'),
(271, 99, 'https://images.unsplash.com/photo-1545558014-8692077e9b5c?w=800'),
(272, 100, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(273, 100, 'https://images.unsplash.com/photo-1563720360172-67b8f3dce741?w=800'),
(274, 100, 'https://images.unsplash.com/photo-1600712242805-5f78671b24da?w=800'),
(275, 101, 'https://images.unsplash.com/photo-1533473359331-0135ef1b58bf?w=800'),
(276, 101, 'https://images.unsplash.com/photo-1519641471654-76ce0107ad1b?w=800'),
(277, 101, 'https://images.unsplash.com/photo-1489824904134-891ab64532f1?w=800'),
(278, 102, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(280, 102, 'https://images.unsplash.com/photo-1605559424843-9e4c228bf1c2?w=800'),
(281, 103, 'https://images.unsplash.com/photo-1503736334956-4c8f8e92946d?w=800'),
(282, 103, 'https://images.unsplash.com/photo-1544636331-e26879cd4d9b?w=800'),
(283, 103, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(296, 108, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(298, 108, 'https://images.unsplash.com/photo-1603584173870-7f23fdae1b7a?w=800'),
(299, 107, 'https://media.vov.vn/sites/default/files/styles/large/public/2024-05/rollsroyce%20cullinanII-2.jpg'),
(300, 106, 'https://giaxeoto.vn/admin/upload/images/maserati-levante-1531451204.jpg'),
(302, 105, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT5iGsNg8OE3CoKCc3y_1Lm68WaZSaXdYM6jQ&s'),
(303, 105, 'https://www.dubicars.com/images/0590db/r_960x540/generations/generation_64a3f8e16660b_bentley-flying-spur-exterior-front-right-angled.jpg?6'),
(304, 104, 'https://giaxeoto.vn/admin/upload/images/resize/640-sieu-xe-mclaren-gt-ra-mat.jpg'),
(305, 104, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSk0P8BbIiUCyMGkyPXHK4ZKQgy8MonePtylw&s'),
(308, 89, 'https://petrolicious.com/cdn/shop/articles/Ferrari-F8-Spider34.jpg?v=1742057337'),
(309, 74, 'https://i1-vnexpress.vnecdn.net/2021/09/19/17601-GhibliGranSportV6.jpg?w=2400&h=0&q=100&dpr=1&fit=crop&s=snJyPIcSclwUVLXOmHLK-g&t=image'),
(310, 74, 'https://giaxeoto.vn/admin/upload/images/resize/640-Maserati-Ghibli-Hybrid.jpg'),
(311, 73, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcS5wpvxyf-VR4vW5G5yNBwifF_hzfP3yOdQmA&s'),
(312, 72, 'https://cdn.tienphong.vn/images/a7a4eb175a75567c9a7ae09768d70948cb277a106057a929e86390352c549fb037cd75cf03922096eef53f1a09a665f1a39aeb7b38c2c0cb9574f71a2657af992149ca6f67ee817c9e3a7f3f3763fe13/2021_Rolls_ROyce_Ghost_3_BFXT.jpg'),
(313, 71, 'https://di-uploads-pod43.dealerinspire.com/mclarensanfrancisco/uploads/2022/04/720s.jpg'),
(314, 70, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQMqe9Fw9U_EA2_UFSm0508QPONanagi5uRqA&s'),
(315, 56, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQkyOtvfEL4KXXDM1hw7gKiF_nGlokWuL5Ggg&s');

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
(8, 'Sedan', 'Xe 4 cửa, thân thấp, khoang hành khách và khoang hành lý tách biệt.'),
(9, 'Hatchback', 'Xe nhỏ gọn, cửa sau mở lên, hàng ghế sau có thể gập.'),
(10, 'SUV (Sport Utility Vehicle)', 'Gầm cao, thân xe lớn, không gian rộng, khả năng vận hành đa địa hình.'),
(11, 'Crossover (CUV)', 'Kết hợp giữa Sedan và SUV, gầm cao nhưng khung liền thân.'),
(12, 'MPV (Multi-Purpose Vehicle – Xe gia đình)', '7–8 chỗ, không gian rộng, tối ưu cho chở người'),
(13, 'Coupe', '2 cửa (hoặc 4 cửa thiết kế thể thao), dáng thấp, phong cách thể thao.'),
(14, 'Bán tải (Pickup)', 'Có thùng hàng phía sau, gầm cao, động cơ mạnh.'),
(16, 'Sports Car', 'Xe thể thao hiệu suất cao.'),
(17, 'Supercar', ''),
(18, 'Hypercar', '');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contacts`
--

CREATE TABLE `contacts` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `subject` varchar(255) DEFAULT NULL,
  `message` text NOT NULL,
  `status` enum('new','processed','unread','read','replied') DEFAULT 'new',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `contacts`
--

INSERT INTO `contacts` (`id`, `name`, `email`, `phone`, `subject`, `message`, `status`, `created_at`) VALUES
(5, 'Nguyễn Tấn Lợi', 'ngtanloi1709@gmail.com', '0368920249', 'laixethu', 'cho lay thu coi', 'new', '2026-01-08 18:06:11');

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

--
-- Đang đổ dữ liệu cho bảng `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `car_id`, `created_at`) VALUES
(15, 3, 103, '2026-01-08 18:02:09'),
(16, 3, 106, '2026-01-09 00:11:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `car_id` int(11) NOT NULL,
  `price` decimal(15,2) NOT NULL,
  `deposit_percentage` int(11) DEFAULT 10,
  `deposit_amount` decimal(15,2) DEFAULT NULL,
  `payment_method` enum('bank_transfer','cash','deposit') NOT NULL,
  `status` enum('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `car_id`, `price`, `deposit_percentage`, `deposit_amount`, `payment_method`, `status`, `notes`, `created_at`) VALUES
(7, 1, 107, 38000000000.00, 0, NULL, 'cash', 'cancelled', '', '2026-01-08 17:53:58'),
(8, 1, 106, 5800000000.00, 0, NULL, 'cash', 'completed', '', '2026-01-08 17:54:42'),
(9, 3, 103, 4500000000.00, 0, NULL, 'bank_transfer', 'completed', 'giau ma', '2026-01-08 18:02:42'),
(10, 3, 105, 18000000000.00, 0, NULL, 'bank_transfer', 'completed', '', '2026-01-08 18:18:14'),
(11, 3, 107, 38000000000.00, 0, NULL, 'cash', 'confirmed', '', '2026-01-08 18:19:27'),
(12, 3, 102, 550000000.00, 0, NULL, 'cash', 'completed', '', '2026-01-08 18:19:51'),
(13, 3, 106, 5800000000.00, 0, NULL, 'cash', 'cancelled', '', '2026-01-09 00:33:40');

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
  `admin_reply` text DEFAULT NULL,
  `replied_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `user_id`, `car_id`, `rating`, `comment`, `admin_reply`, `replied_at`, `created_at`) VALUES
(4, 3, 103, 5, 'xe dep qua troi, chac mua them 3-4 chiec nua', 'ok thanks bro', '2026-01-08 18:04:16', '2026-01-08 18:03:45');

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

INSERT INTO `users` (`id`, `username`, `email`, `password`, `full_name`, `phone`, `address`, `avatar`, `remember_token`, `role`, `status`, `created_at`) VALUES
(1, 'khanhdang', 'khanhdang2440@gmail.com', '$2y$10$SzFXIlqlISyBJEq.ReXYs.zp3DgTjSlcDh1hvCotF.IHbmra9DXE.', 'Lê Khánh Đăng', '0868065672', NULL, NULL, '9587e6c188c3f0f2a6029ba6868fe7c68a0b99183dc4f84b9ab8c38b0845f088', 'admin', 1, '2025-12-30 07:21:32'),
(3, 'ngtanloi1709', 'ngtanloi1709@gmail.com', '$2y$10$oUgnF.Z7dh9/IySuq.KDzOMQ5ostOmljv4YzS5.hnqWb1kbtpIWGq', 'Nguyễn Tấn Lợi', '0368920249', NULL, NULL, NULL, 'user', 1, '2026-01-08 12:01:55');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `cars`
--
ALTER TABLE `cars`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT cho bảng `car_images`
--
ALTER TABLE `car_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=316;

--
-- AUTO_INCREMENT cho bảng `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT cho bảng `contacts`
--
ALTER TABLE `contacts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
