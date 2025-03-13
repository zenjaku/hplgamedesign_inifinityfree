<?php
/**
 * Database Connection Configuration
 * 
 * Establishes database connection based on environment (development/production)
 * Implements basic security measures and error handling
 * 
 * @package HPL-Inventory
 * @version 1.0
 */

// Environment-based configuration
$environment = in_array($_SERVER['SERVER_NAME'], ['localhost', '127.0.0.1']) ? 'development' : 'production';

// Database credentials
$config = [
    'development' => [
        'host' => 'localhost',
        'user' => 'root',
        'pass' => '',
        'db'   => 'hpl'
    ],
    'production' => [
        'host' => 'sql300.infinityfree.com',
        'user' => 'if0_38196116',
        'pass' => 'hplgamedesign',
        'db'   => 'if0_38196116_hpl'
    ]
];

// Create connection using environment config
try {
    $conn = new mysqli(
        $config[$environment]['host'],
        $config[$environment]['user'],
        $config[$environment]['pass'],
        $config[$environment]['db']
    );

    if ($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }

    // Set timezone and character set
    date_default_timezone_set('Asia/Manila');
    $conn->set_charset("utf8mb4");
    
} catch (Exception $e) {
    // Log error and display generic message
    error_log($e->getMessage());
    die("A database error occurred. Please try again later.");
}
?>
