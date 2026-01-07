<?php
// config/database.php

class Database
{
    private static $instance = null;
    private $conn;

    private function __construct()
    {
        $config = require __DIR__ . '/config.php';
        $db = $config['db'];

        try {
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $this->conn = new PDO($dsn, $db['username'], $db['password']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi kết nối CSDL: " . $e->getMessage());
        }
    }

    // Singleton
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection()
    {
        // Kiểm tra và reconnect nếu cần
        $this->ping();
        return $this->conn;
    }

    // Kiểm tra kết nối MySQL
    private function ping()
    {
        try {
            $this->conn->query('SELECT 1');
        } catch (PDOException $e) {
            // Kết nối bị mất, reconnect
            $this->reconnect();
        }
    }

    // Reconnect MySQL
    private function reconnect()
    {
        $config = require __DIR__ . '/config.php';
        $db = $config['db'];

        try {
            $dsn = "mysql:host={$db['host']};dbname={$db['dbname']};charset={$db['charset']}";
            $this->conn = new PDO($dsn, $db['username'], $db['password']);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Lỗi kết nối lại CSDL: " . $e->getMessage());
        }
    }

    // Refresh connection
    public function refreshConnection()
    {
        $this->reconnect();
        return $this->conn;
    }
}
