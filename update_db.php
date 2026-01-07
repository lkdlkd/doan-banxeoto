<?php
require_once __DIR__ . '/config/database.php';
$db = Database::getInstance()->getConnection();

echo "<pre>";

// Kiểm tra và thêm cột avatar nếu chưa có
try {
    $result = $db->query("SHOW COLUMNS FROM users LIKE 'avatar'");
    if ($result->rowCount() == 0) {
        $db->exec("ALTER TABLE users ADD COLUMN avatar VARCHAR(255) DEFAULT NULL AFTER address");
        echo "Added avatar column\n";
    } else {
        echo "Avatar column already exists\n";
    }
} catch (Exception $e) {
    echo "Error with avatar: " . $e->getMessage() . "\n";
}

// Kiểm tra và thêm cột remember_token nếu chưa có
try {
    $result = $db->query("SHOW COLUMNS FROM users LIKE 'remember_token'");
    if ($result->rowCount() == 0) {
        $db->exec("ALTER TABLE users ADD COLUMN remember_token VARCHAR(100) DEFAULT NULL AFTER avatar");
        echo "Added remember_token column\n";
    } else {
        echo "Remember_token column already exists\n";
    }
} catch (Exception $e) {
    echo "Error with remember_token: " . $e->getMessage() . "\n";
}

// Thêm các cột thông số kỹ thuật cho bảng cars
$technicalColumns = [
    'engine' => "ALTER TABLE cars ADD COLUMN engine VARCHAR(100) DEFAULT NULL COMMENT 'Loại động cơ' AFTER description",
    'horsepower' => "ALTER TABLE cars ADD COLUMN horsepower INT DEFAULT NULL COMMENT 'Công suất (HP)' AFTER engine",
    'torque' => "ALTER TABLE cars ADD COLUMN torque INT DEFAULT NULL COMMENT 'Mô-men xoắn (Nm)' AFTER horsepower",
    'seats' => "ALTER TABLE cars ADD COLUMN seats INT DEFAULT NULL COMMENT 'Số chỗ ngồi' AFTER torque",
    'doors' => "ALTER TABLE cars ADD COLUMN doors INT DEFAULT NULL COMMENT 'Số cửa' AFTER seats",
    'drivetrain' => "ALTER TABLE cars ADD COLUMN drivetrain VARCHAR(50) DEFAULT NULL COMMENT 'Hệ dẫn động' AFTER doors",
    'acceleration' => "ALTER TABLE cars ADD COLUMN acceleration DECIMAL(3,1) DEFAULT NULL COMMENT 'Tăng tốc 0-100 km/h (s)' AFTER drivetrain"
];

foreach ($technicalColumns as $columnName => $query) {
    try {
        $result = $db->query("SHOW COLUMNS FROM cars LIKE '$columnName'");
        if ($result->rowCount() == 0) {
            $db->exec($query);
            echo "✓ Added cars.$columnName column\n";
        } else {
            echo "⊘ cars.$columnName column already exists\n";
        }
    } catch (Exception $e) {
        echo "✗ Error with cars.$columnName: " . $e->getMessage() . "\n";
    }
}

// Cập nhật dữ liệu mẫu cho các xe hiện có
echo "\n--- Updating sample car data ---\n";

$sampleData = [
    1 => ['V8 4.0L Twin-Turbo', 639, 900, 4, 4, 'AWD', 3.2], // AMG GT 63 S
    2 => ['V6 3.0L Turbo', 367, 500, 5, 4, 'AWD', 5.4],       // S-Class S450
    4 => ['V8 4.4L Twin-Turbo', 617, 750, 4, 2, 'AWD', 3.2],  // M8 Competition
    10 => ['Flat-6 3.8L Twin-Turbo', 640, 800, 4, 2, 'AWD', 2.7], // 911 Turbo S
    13 => ['V8 3.9L Twin-Turbo', 720, 770, 2, 2, 'RWD', 2.9], // F8 Tributo
    16 => ['V10 5.2L', 640, 600, 2, 2, 'AWD', 2.9],           // Huracán EVO
    24 => ['V12 6.75L Twin-Turbo', 563, 900, 5, 4, 'RWD', 5.3] // Phantom
];

foreach ($sampleData as $carId => $specs) {
    try {
        $stmt = $db->prepare("UPDATE cars SET engine = ?, horsepower = ?, torque = ?, seats = ?, doors = ?, drivetrain = ?, acceleration = ? WHERE id = ?");
        $stmt->execute([
            $specs[0], // engine
            $specs[1], // horsepower
            $specs[2], // torque
            $specs[3], // seats
            $specs[4], // doors
            $specs[5], // drivetrain
            $specs[6], // acceleration
            $carId
        ]);
        if ($stmt->rowCount() > 0) {
            echo "✓ Updated car ID $carId with technical specs\n";
        }
    } catch (Exception $e) {
        echo "✗ Error updating car ID $carId: " . $e->getMessage() . "\n";
    }
}

echo "\n";

// Tạo tài khoản admin mẫu nếu chưa có
try {
    $stmt = $db->prepare("SELECT COUNT(*) FROM users WHERE role = 'admin'");
    $stmt->execute();
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        $password = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute(['admin', 'admin@autocar.vn', $password, 'Administrator', 'admin']);
        echo "Created admin account:\n";
        echo "  Email: admin@autocar.vn\n";
        echo "  Password: admin123\n";
    } else {
        echo "Admin account already exists\n";
    }
} catch (Exception $e) {
    echo "Error creating admin: " . $e->getMessage() . "\n";
}

echo "\n✓ Database updated successfully!\n";
echo "</pre>";
