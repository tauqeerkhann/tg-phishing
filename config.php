<?php
/**
 * CONFIGURATION FILE
 * Educational Phishing Simulation Platform
 * FOR DEMONSTRATION PURPOSES ONLY
 */

// Enable error reporting for development
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/error.log');

// Session configuration
session_start();
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => $_SERVER['HTTP_HOST'] ?? 'localhost',
    'secure' => isset($_SERVER['HTTPS']),
    'httponly' => true,
    'samesite' => 'Strict'
]);

// Application constants
define('APP_NAME', 'Security Awareness Test');
define('APP_VERSION', '2.1.0');
define('DATA_FILE', __DIR__ . '/users.json');
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD_HASH', password_hash('admin123', PASSWORD_DEFAULT));

// Platform configurations
$PLATFORMS = [
    'facebook' => [
        'name' => 'Facebook Security Check',
        'logo' => 'https://img.icons8.com/color/96/000000/facebook-new.png',
        'color' => '#1877F2',
        'url_pattern' => 'https://facebook.com/security-check',
        'description' => 'Test your Facebook login security'
    ],
    'instagram' => [
        'name' => 'Instagram Login Verification',
        'logo' => 'https://img.icons8.com/color/96/000000/instagram-new.png',
        'color' => '#E4405F',
        'url_pattern' => 'https://instagram.com/accounts/login',
        'description' => 'Verify your Instagram account security'
    ],
    'google' => [
        'name' => 'Google Account Security Scan',
        'logo' => 'https://img.icons8.com/color/96/000000/google-logo.png',
        'color' => '#4285F4',
        'url_pattern' => 'https://accounts.google.com/signin',
        'description' => 'Scan for Google account vulnerabilities'
    ],
    'banking' => [
        'name' => 'Banking Login Test',
        'logo' => 'https://img.icons8.com/color/96/000000/bank.png',
        'color' => '#1E8449',
        'url_pattern' => 'https://secure.bank.example.com/login',
        'description' => 'Test banking login security protocols'
    ],
    'email' => [
        'name' => 'Email Security Audit',
        'logo' => 'https://img.icons8.com/color/96/000000/email.png',
        'color' => '#EA4335',
        'url_pattern' => 'https://mail.example.com/login',
        'description' => 'Audit your email account security'
    ]
];

// Security tips
$SECURITY_TIPS = [
    'Always check the URL in the address bar before entering credentials',
    'Look for HTTPS and a valid SSL certificate',
    'Never click on links in suspicious emails',
    'Enable two-factor authentication whenever possible',
    'Use unique passwords for different accounts',
    'Verify the sender email address in security emails',
    'Be cautious of urgent or threatening messages',
    'Keep your software and browsers updated'
];

// Function to log attempts
function logAttempt($data) {
    $file = DATA_FILE;
    
    // Initialize if file doesn't exist
    if (!file_exists($file)) {
        $initialData = [
            'attempts' => [],
            'statistics' => [
                'total_tests' => 0,
                'unique_visitors' => 0,
                'platforms' => [
                    'facebook' => 0,
                    'instagram' => 0,
                    'google' => 0,
                    'banking' => 0,
                    'email' => 0
                ]
            ],
            'meta' => [
                'created' => date('Y-m-d H:i:s'),
                'last_updated' => date('Y-m-d H:i:s')
            ]
        ];
        file_put_contents($file, json_encode($initialData, JSON_PRETTY_PRINT));
    }
    
    $json = json_decode(file_get_contents($file), true);
    
    // Generate unique ID
    $id = uniqid('attempt_', true);
    
    // Create attempt record
    $attempt = [
        'id' => $id,
        'timestamp' => date('Y-m-d H:i:s'),
        'platform' => $data['platform'],
        'username' => substr($data['username'], 0, 100), // Limit length
        'password' => str_repeat('*', strlen($data['password'])), // Hide real password
        'ip_address' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown',
        'educational_viewed' => false,
        'is_simulation' => true // Always true for this demo
    ];
    
    // Add attempt
    $json['attempts'][] = $attempt;
    
    // Update statistics
    $json['statistics']['total_tests']++;
    if (isset($json['statistics']['platforms'][$data['platform']])) {
        $json['statistics']['platforms'][$data['platform']]++;
    }
    
    // Count unique visitors (simplified)
    $visitorKey = md5($attempt['ip_address'] . $attempt['user_agent']);
    if (!isset($json['statistics']['visitors'])) {
        $json['statistics']['visitors'] = [];
    }
    if (!in_array($visitorKey, $json['statistics']['visitors'])) {
        $json['statistics']['visitors'][] = $visitorKey;
        $json['statistics']['unique_visitors'] = count($json['statistics']['visitors']);
    }
    
    $json['meta']['last_updated'] = date('Y-m-d H:i:s');
    
    // Save to file
    file_put_contents($file, json_encode($json, JSON_PRETTY_PRINT));
    
    return $id;
}

// Function to get statistics
function getStatistics() {
    $file = DATA_FILE;
    
    if (!file_exists($file)) {
        return [
            'total_tests' => 0,
            'unique_visitors' => 0,
            'platforms' => [
                'facebook' => 0,
                'instagram' => 0,
                'google' => 0,
                'banking' => 0,
                'email' => 0
            ]
        ];
    }
    
    $json = json_decode(file_get_contents($file), true);
    return $json['statistics'] ?? [];
}

// Function to get all attempts (admin only)
function getAllAttempts() {
    $file = DATA_FILE;
    
    if (!file_exists($file)) {
        return [];
    }
    
    $json = json_decode(file_get_contents($file), true);
    return $json['attempts'] ?? [];
}

// Function to reset data (admin only)
function resetAllData() {
    $file = DATA_FILE;
    
    $initialData = [
        'attempts' => [],
        'statistics' => [
            'total_tests' => 0,
            'unique_visitors' => 0,
            'platforms' => [
                'facebook' => 0,
                'instagram' => 0,
                'google' => 0,
                'banking' => 0,
                'email' => 0
            ],
            'visitors' => []
        ],
        'meta' => [
            'created' => date('Y-m-d H:i:s'),
            'last_updated' => date('Y-m-d H:i:s')
        ]
    ];
    
    return file_put_contents($file, json_encode($initialData, JSON_PRETTY_PRINT));
}

// CSRF Protection
function generateCSRFToken() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function validateCSRFToken($token) {
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}

// Initialize CSRF token
generateCSRFToken();
?>