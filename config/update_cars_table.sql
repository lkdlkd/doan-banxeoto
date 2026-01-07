-- Cập nhật cấu trúc bảng cars để thêm các thông số kỹ thuật
-- Chạy script này trong phpMyAdmin hoặc MySQL CLI

USE `banxeoto`;

-- Thêm các cột mới vào bảng cars
ALTER TABLE `cars` 
ADD COLUMN `stock` int(11) DEFAULT 1 AFTER `status`,
ADD COLUMN `engine` varchar(100) DEFAULT NULL AFTER `stock`,
ADD COLUMN `horsepower` int(11) DEFAULT NULL AFTER `engine`,
ADD COLUMN `torque` int(11) DEFAULT NULL AFTER `horsepower`,
ADD COLUMN `acceleration` decimal(4,1) DEFAULT NULL AFTER `torque`,
ADD COLUMN `drivetrain` varchar(20) DEFAULT NULL AFTER `acceleration`,
ADD COLUMN `seats` int(11) DEFAULT NULL AFTER `drivetrain`,
ADD COLUMN `doors` int(11) DEFAULT NULL AFTER `seats`;

-- Cập nhật dữ liệu mẫu cho các xe hiện có
UPDATE `cars` SET 
    `stock` = 1,
    `seats` = 5,
    `doors` = 4
WHERE `category_id` IN (1, 2); -- Sedan và SUV

UPDATE `cars` SET 
    `stock` = 1,
    `seats` = 2,
    `doors` = 2
WHERE `category_id` = 5; -- Supercar

UPDATE `cars` SET 
    `stock` = 1,
    `seats` = 4,
    `doors` = 2
WHERE `category_id` = 3; -- Coupe

-- Cập nhật thông số kỹ thuật cho một số xe nổi bật
UPDATE `cars` SET 
    `engine` = 'V8 4.0L Twin-Turbo',
    `horsepower` = 639,
    `torque` = 900,
    `acceleration` = 3.2,
    `drivetrain` = 'AWD'
WHERE `id` = 1; -- AMG GT 63 S

UPDATE `cars` SET 
    `engine` = 'V8 4.4L Twin-Turbo',
    `horsepower` = 617,
    `torque` = 750,
    `acceleration` = 3.2,
    `drivetrain` = 'AWD'
WHERE `id` = 4; -- M8 Competition

UPDATE `cars` SET 
    `engine` = 'Flat-6 3.8L Twin-Turbo',
    `horsepower` = 640,
    `torque` = 800,
    `acceleration` = 2.7,
    `drivetrain` = 'AWD'
WHERE `id` = 10; -- 911 Turbo S

UPDATE `cars` SET 
    `engine` = 'V8 3.9L Twin-Turbo',
    `horsepower` = 720,
    `torque` = 770,
    `acceleration` = 2.9,
    `drivetrain` = 'RWD'
WHERE `id` = 13; -- F8 Tributo

UPDATE `cars` SET 
    `engine` = 'V8 4.0L Hybrid',
    `horsepower` = 1000,
    `torque` = 800,
    `acceleration` = 2.5,
    `drivetrain` = 'AWD'
WHERE `id` = 14; -- SF90 Stradale

UPDATE `cars` SET 
    `engine` = 'V10 5.2L',
    `horsepower` = 640,
    `torque` = 600,
    `acceleration` = 2.9,
    `drivetrain` = 'AWD'
WHERE `id` = 16; -- Huracán EVO

UPDATE `cars` SET 
    `engine` = 'V8 4.0L Twin-Turbo',
    `horsepower` = 666,
    `torque` = 850,
    `acceleration` = 3.3,
    `drivetrain` = 'AWD',
    `seats` = 5,
    `doors` = 4
WHERE `id` = 17; -- Urus S

UPDATE `cars` SET 
    `engine` = 'V12 6.5L Hybrid',
    `horsepower` = 1015,
    `torque` = 807,
    `acceleration` = 2.5,
    `drivetrain` = 'AWD'
WHERE `id` = 18; -- Revuelto

UPDATE `cars` SET 
    `engine` = 'V8 4.0L Twin-Turbo',
    `horsepower` = 720,
    `torque` = 770,
    `acceleration` = 2.8,
    `drivetrain` = 'RWD'
WHERE `id` = 19; -- 720S Spider

UPDATE `cars` SET 
    `engine` = 'W12 6.0L Twin-Turbo',
    `horsepower` = 659,
    `torque` = 900,
    `acceleration` = 3.6,
    `drivetrain` = 'AWD'
WHERE `id` = 21; -- Continental GT

UPDATE `cars` SET 
    `engine` = 'V12 6.75L Twin-Turbo',
    `horsepower` = 571,
    `torque` = 900,
    `acceleration` = 5.4,
    `drivetrain` = 'RWD',
    `seats` = 5,
    `doors` = 4
WHERE `id` = 24; -- Phantom

UPDATE `cars` SET 
    `engine` = 'V12 6.75L Twin-Turbo',
    `horsepower` = 600,
    `torque` = 900,
    `acceleration` = 4.8,
    `drivetrain` = 'AWD',
    `seats` = 5,
    `doors` = 4
WHERE `id` = 25; -- Cullinan

UPDATE `cars` SET 
    `engine` = 'V6 3.0L Twin-Turbo',
    `horsepower` = 630,
    `torque` = 730,
    `acceleration` = 2.9,
    `drivetrain` = 'RWD'
WHERE `id` = 27; -- MC20

UPDATE `cars` SET 
    `engine` = 'V8 5.0L',
    `horsepower` = 471,
    `torque` = 540,
    `acceleration` = 4.7,
    `drivetrain` = 'RWD'
WHERE `id` = 29; -- LC 500

UPDATE `cars` SET 
    `engine` = 'I4 2.5L',
    `horsepower` = 209,
    `torque` = 250,
    `acceleration` = 8.5,
    `drivetrain` = 'FWD',
    `seats` = 5,
    `doors` = 4
WHERE `id` = 31; -- Camry

UPDATE `cars` SET 
    `engine` = 'V6 3.5L Twin-Turbo',
    `horsepower` = 409,
    `torque` = 650,
    `acceleration` = 6.7,
    `drivetrain` = '4WD',
    `seats` = 7,
    `doors` = 4
WHERE `id` = 32; -- Land Cruiser

-- Kiểm tra kết quả
SELECT `id`, `name`, `stock`, `engine`, `horsepower`, `acceleration`, `seats` FROM `cars` LIMIT 10;
