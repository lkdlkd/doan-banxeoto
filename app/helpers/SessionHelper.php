<?php
// app/helpers/SessionHelper.php

class SessionHelper
{
    // Khởi động session
    public static function start()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Kiểm tra đã đăng nhập chưa
    public static function isLoggedIn()
    {
        self::start();
        return isset($_SESSION['user_id']);
    }

    // Kiểm tra có phải admin không
    public static function isAdmin()
    {
        self::start();
        return isset($_SESSION['role']) && $_SESSION['role'] === 'admin';
    }

    // Lấy user ID hiện tại
    public static function getUserId()
    {
        self::start();
        return $_SESSION['user_id'] ?? null;
    }

    // Lấy username hiện tại
    public static function getUsername()
    {
        self::start();
        return $_SESSION['username'] ?? null;
    }

    // Lấy full name hiện tại
    public static function getFullName()
    {
        self::start();
        return $_SESSION['full_name'] ?? null;
    }

    // Lấy email hiện tại
    public static function getEmail()
    {
        self::start();
        return $_SESSION['email'] ?? null;
    }

    // Lấy role hiện tại
    public static function getRole()
    {
        self::start();
        return $_SESSION['role'] ?? null;
    }

    // Yêu cầu đăng nhập - redirect nếu chưa login
    public static function requireLogin()
    {
        self::start();
        if (!self::isLoggedIn()) {
            $_SESSION['error'] = 'Vui lòng đăng nhập để tiếp tục';
            header('Location: /login');
            exit;
        }
    }

    // Yêu cầu quyền admin - redirect nếu không phải admin
    public static function requireAdmin()
    {
        self::start();
        self::requireLogin();
        if (!self::isAdmin()) {
            $_SESSION['error'] = 'Bạn không có quyền truy cập trang này';
            header('Location: /');
            exit;
        }
    }

    // Set flash message
    public static function setFlash($key, $message)
    {
        self::start();
        $_SESSION['flash'][$key] = $message;
    }

    // Get và xóa flash message
    public static function getFlash($key)
    {
        self::start();
        if (isset($_SESSION['flash'][$key])) {
            $message = $_SESSION['flash'][$key];
            unset($_SESSION['flash'][$key]);
            return $message;
        }
        return null;
    }

    // Kiểm tra có flash message không
    public static function hasFlash($key)
    {
        self::start();
        return isset($_SESSION['flash'][$key]);
    }

    // Xóa tất cả session
    public static function destroy()
    {
        self::start();
        session_unset();
        session_destroy();
    }
}
