<?php
// Security configuration
define('SECURE', true);

// Session security - only set if session is not active
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.cookie_secure', 0); // Set to 1 when using HTTPS
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Strict');
}

// Database configuration - Move these to environment variables in production
$_ENV['DB_HOST'] = $_ENV['DB_HOST'] ?? 'localhost';
$_ENV['DB_USER'] = $_ENV['DB_USER'] ?? 'root';
$_ENV['DB_PASS'] = $_ENV['DB_PASS'] ?? '';
$_ENV['DB_NAME'] = $_ENV['DB_NAME'] ?? 'unimind';

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Rate limiting
$rate_limit = [
    'login_attempts' => 5,
    'lockout_time' => 900, // 15 minutes
    'session_timeout' => 3600 // 1 hour
];

// Password requirements
$password_requirements = [
    'min_length' => 8,
    'require_uppercase' => true,
    'require_lowercase' => true,
    'require_number' => true,
    'require_special' => true
];
?>
