-- Sample Data for AutoCar Website
-- Run this after creating the database structure

USE `banxeoto`;

-- Insert Brands (Luxury Car Brands)
INSERT INTO `brands` (`id`, `name`, `logo`, `description`) VALUES
(1, 'Mercedes-Benz', 'https://www.carlogos.org/car-logos/mercedes-benz-logo.png', 'Thương hiệu xe sang Đức'),
(2, 'BMW', 'https://www.carlogos.org/car-logos/bmw-logo.png', 'Bayerische Motoren Werke AG'),
(3, 'Audi', 'https://www.carlogos.org/car-logos/audi-logo.png', 'Thương hiệu xe sang Đức'),
(4, 'Porsche', 'https://www.carlogos.org/car-logos/porsche-logo.png', 'Thương hiệu xe thể thao cao cấp Đức'),
(5, 'Ferrari', 'https://www.carlogos.org/car-logos/ferrari-logo.png', 'Hãng siêu xe Italia'),
(6, 'Lamborghini', 'https://www.carlogos.org/car-logos/lamborghini-logo.png', 'Hãng siêu xe Italia'),
(7, 'McLaren', 'https://www.carlogos.org/car-logos/mclaren-logo.png', 'Hãng siêu xe Anh Quốc'),
(8, 'Bentley', 'https://www.carlogos.org/car-logos/bentley-logo.png', 'Xe siêu sang Anh Quốc'),
(9, 'Rolls-Royce', 'https://www.carlogos.org/car-logos/rolls-royce-logo.png', 'Xe siêu sang Anh Quốc'),
(10, 'Maserati', 'https://www.carlogos.org/car-logos/maserati-logo.png', 'Xe sang thể thao Italia'),
(11, 'Lexus', 'https://www.carlogos.org/car-logos/lexus-logo.png', 'Thương hiệu xe sang Nhật Bản'),
(12, 'Toyota', 'https://www.carlogos.org/car-logos/toyota-logo.png', 'Thương hiệu xe Nhật Bản hàng đầu');

-- Insert Categories
INSERT INTO `categories` (`id`, `name`, `description`) VALUES
(1, 'Sedan', 'Xe sedan 4 cửa'),
(2, 'SUV', 'Sport Utility Vehicle'),
(3, 'Coupe', 'Xe thể thao 2 cửa'),
(4, 'Convertible', 'Xe mui trần'),
(5, 'Supercar', 'Siêu xe'),
(6, 'Hatchback', 'Xe 5 cửa compact'),
(7, 'Crossover', 'Xe gầm cao đa dụng');

-- Insert Cars with realistic VND prices
INSERT INTO `cars` (`id`, `name`, `brand_id`, `category_id`, `price`, `year`, `mileage`, `fuel`, `transmission`, `color`, `description`, `status`) VALUES
-- Mercedes-Benz
(1, 'AMG GT 63 S', 1, 3, 12500000000, 2024, 0, 'gasoline', 'automatic', 'Đen', 'Mercedes-AMG GT 63 S 4MATIC+ với động cơ V8 4.0L twin-turbo 639 mã lực', 'available'),
(2, 'S-Class S450', 1, 1, 5200000000, 2024, 0, 'gasoline', 'automatic', 'Trắng', 'Mercedes-Benz S450 4MATIC Luxury sedan flagship', 'available'),
(3, 'GLE 450 AMG', 1, 2, 4800000000, 2024, 5000, 'gasoline', 'automatic', 'Xám', 'Mercedes GLE 450 4MATIC Coupe AMG Line', 'available'),

-- BMW
(4, 'M8 Competition', 2, 3, 13200000000, 2024, 0, 'gasoline', 'automatic', 'Xanh San Marino', 'BMW M8 Competition Coupe với động cơ V8 4.4L 617 mã lực', 'available'),
(5, 'X7 xDrive40i', 2, 2, 6800000000, 2024, 3000, 'gasoline', 'automatic', 'Đen Sapphire', 'BMW X7 xDrive40i M Sport 7 chỗ luxury SUV', 'available'),
(6, '7 Series 740i', 2, 1, 5900000000, 2025, 0, 'gasoline', 'automatic', 'Trắng Alpine', 'BMW 740i M Sport flagship sedan', 'available'),

-- Audi
(7, 'RS7 Sportback', 3, 1, 8900000000, 2024, 0, 'gasoline', 'automatic', 'Xám Nardo', 'Audi RS7 Sportback với động cơ V8 4.0L TFSI 621 mã lực', 'available'),
(8, 'Q8 55 TFSI', 3, 2, 5500000000, 2024, 8000, 'gasoline', 'automatic', 'Đen', 'Audi Q8 55 TFSI quattro S line coupe SUV', 'available'),
(9, 'e-tron GT', 3, 1, 6500000000, 2024, 2000, 'electric', 'automatic', 'Xanh Kemora', 'Audi e-tron GT quattro điện cao cấp', 'available'),

-- Porsche
(10, '911 Turbo S', 4, 5, 15800000000, 2025, 0, 'gasoline', 'automatic', 'Vàng Racing', 'Porsche 911 Turbo S với động cơ flat-6 3.8L 640 mã lực', 'available'),
(11, 'Cayenne Turbo GT', 4, 2, 12500000000, 2024, 0, 'gasoline', 'automatic', 'Xanh Gentian', 'Porsche Cayenne Turbo GT với 640 mã lực', 'available'),
(12, 'Panamera 4S E-Hybrid', 4, 1, 8200000000, 2024, 5000, 'gasoline', 'automatic', 'Đen Jet', 'Porsche Panamera 4S E-Hybrid Sport Turismo', 'available'),

-- Ferrari
(13, 'F8 Tributo', 5, 5, 21500000000, 2024, 0, 'gasoline', 'automatic', 'Đỏ Rosso Corsa', 'Ferrari F8 Tributo với động cơ V8 3.9L twin-turbo 720 mã lực', 'available'),
(14, 'SF90 Stradale', 5, 5, 35000000000, 2024, 0, 'gasoline', 'automatic', 'Đỏ Rosso Scuderia', 'Ferrari SF90 Stradale hybrid 1000 mã lực', 'available'),
(15, 'Roma', 5, 3, 18500000000, 2024, 3000, 'gasoline', 'automatic', 'Trắng Bianco', 'Ferrari Roma grand touring coupe', 'available'),

-- Lamborghini
(16, 'Huracán EVO', 6, 5, 19800000000, 2024, 0, 'gasoline', 'automatic', 'Xanh Verde Mantis', 'Lamborghini Huracán EVO với động cơ V10 5.2L 640 mã lực', 'available'),
(17, 'Urus S', 6, 2, 16500000000, 2024, 5000, 'gasoline', 'automatic', 'Vàng Giallo', 'Lamborghini Urus S Super SUV 666 mã lực', 'available'),
(18, 'Revuelto', 6, 5, 45000000000, 2025, 0, 'gasoline', 'automatic', 'Cam Arancio', 'Lamborghini Revuelto V12 hybrid mới nhất', 'available'),

-- McLaren
(19, '720S Spider', 7, 5, 23500000000, 2024, 0, 'gasoline', 'automatic', 'Cam McLaren', 'McLaren 720S Spider với động cơ V8 4.0L twin-turbo 720 mã lực', 'available'),
(20, 'Artura', 7, 5, 16800000000, 2024, 2000, 'gasoline', 'automatic', 'Xanh Ludus', 'McLaren Artura hybrid supercar', 'available'),

-- Bentley
(21, 'Continental GT', 8, 3, 18900000000, 2025, 0, 'gasoline', 'automatic', 'Xanh Verdant', 'Bentley Continental GT Speed với động cơ W12 659 mã lực', 'available'),
(22, 'Flying Spur', 8, 1, 16500000000, 2024, 0, 'gasoline', 'automatic', 'Đen Onyx', 'Bentley Flying Spur V8 luxury sedan', 'available'),
(23, 'Bentayga', 8, 2, 15200000000, 2024, 8000, 'gasoline', 'automatic', 'Trắng Ghost', 'Bentley Bentayga S luxury SUV', 'available'),

-- Rolls-Royce
(24, 'Phantom', 9, 1, 55000000000, 2024, 0, 'gasoline', 'automatic', 'Đen Diamond', 'Rolls-Royce Phantom Extended flagship sedan', 'available'),
(25, 'Cullinan', 9, 2, 42000000000, 2024, 0, 'gasoline', 'automatic', 'Xanh Peacock', 'Rolls-Royce Cullinan Black Badge SUV', 'available'),
(26, 'Ghost', 9, 1, 35000000000, 2024, 5000, 'gasoline', 'automatic', 'Bạc Silver', 'Rolls-Royce Ghost luxury sedan', 'available'),

-- Maserati
(27, 'MC20', 10, 5, 15800000000, 2024, 0, 'gasoline', 'automatic', 'Xanh Blu Infinito', 'Maserati MC20 siêu xe với động cơ V6 Nettuno 630 mã lực', 'available'),
(28, 'GranTurismo', 10, 3, 12500000000, 2025, 0, 'gasoline', 'automatic', 'Đỏ Rosso', 'Maserati GranTurismo Trofeo grand touring', 'available'),

-- Lexus
(29, 'LC 500', 11, 3, 8500000000, 2024, 0, 'gasoline', 'automatic', 'Vàng Flare', 'Lexus LC 500 coupe với động cơ V8 5.0L 471 mã lực', 'available'),
(30, 'LX 600', 11, 2, 9200000000, 2024, 10000, 'gasoline', 'automatic', 'Trắng Sonic', 'Lexus LX 600 F Sport flagship SUV', 'available'),

-- Toyota (affordable options)
(31, 'Camry 2.5Q', 12, 1, 1420000000, 2024, 0, 'gasoline', 'automatic', 'Trắng Ngọc Trai', 'Toyota Camry 2.5Q sedan cao cấp', 'available'),
(32, 'Land Cruiser', 12, 2, 4090000000, 2024, 0, 'gasoline', 'automatic', 'Đen', 'Toyota Land Cruiser 300 VXR flagship SUV', 'available'),
(33, 'Corolla Cross HEV', 12, 7, 920000000, 2024, 5000, 'gasoline', 'automatic', 'Xám', 'Toyota Corolla Cross Hybrid crossover', 'available');

-- Insert Car Images
INSERT INTO `car_images` (`car_id`, `image_url`) VALUES
(1, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(2, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(3, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(4, 'https://images.unsplash.com/photo-1617531653332-bd46c24f2068?w=800'),
(5, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(6, 'https://images.unsplash.com/photo-1555215695-3004980ad54e?w=800'),
(7, 'https://images.unsplash.com/photo-1580273916550-e323be2ae537?w=800'),
(8, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(9, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(10, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(11, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(12, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(13, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(14, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(15, 'https://images.unsplash.com/photo-1583121274602-3e2820c69888?w=800'),
(16, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(17, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(18, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(19, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(20, 'https://images.unsplash.com/photo-1503376780353-7e6692767b70?w=800'),
(21, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(22, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(23, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(24, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(25, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(26, 'https://images.unsplash.com/photo-1549399542-7e3f8b79c341?w=800'),
(27, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(28, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(29, 'https://images.unsplash.com/photo-1618843479313-40f8afb4b4d8?w=800'),
(30, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(31, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800'),
(32, 'https://images.unsplash.com/photo-1606664515524-ed2f786a0bd6?w=800'),
(33, 'https://images.unsplash.com/photo-1609521263047-f8f205293f24?w=800');
