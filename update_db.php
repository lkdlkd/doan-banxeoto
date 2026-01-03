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

echo "\nDatabase updated successfully!\n";
echo "</pre>";
