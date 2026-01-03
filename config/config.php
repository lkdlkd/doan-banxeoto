<?php
// config/config.php

// Detect base URL automatically
if (!defined('BASE_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']);
    $baseUrl = ($scriptDir === '/' || $scriptDir === '\\') ? '' : $scriptDir;
    define('BASE_URL', $baseUrl);
}

return [
    'db' => [
        'host'     => 'localhost',
        'dbname'   => 'banxeoto',
        'username' => 'root',
        'password' => '',
        'charset'  => 'utf8mb4'
    ]
];
