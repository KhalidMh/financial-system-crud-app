<?php

return [
    'host' => $_ENV['DB_HOST'] ?? 'mysql',
    'dbname' => $_ENV['DB_NAME'] ?? 'app',
    'username' => $_ENV['DB_USER'] ?? 'user',
    'password' => $_ENV['DB_PASS'] ?? 'password',
    'charset' => 'utf8mb4',
    'options' => [
        // Enable exceptions for error handling
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        // Set default fetch mode to associative array
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        // Disable emulation of prepared statements for better security
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
