<?php
require_once 'config.php';

// Get platform from URL
$platformKey = $_GET['platform'] ?? 'facebook';
$platform = $PLATFORMS[$platformKey] ?? $PLATFORMS['facebook'];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['csrf_token']) && validateCSRFToken($_POST['csrf_token'])) {
    // Simulate credential capture (educational purposes only)
    $username = $_POST['username'] ?? 'demo_user';
    $password = $_POST['password'] ?? '';
    
    // Log the attempt
    $attemptId = logAttempt([
        'platform' => $platformKey,
        'username' => $username,
        'password' => $password
    ]);
    
    // Store in session for results page
    $_SESSION['last_attempt'] = [
        'id' => $attemptId,
        'platform' => $platformKey,
        'username' => $username,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    // Redirect to verification page
    header('Location: verify.php?platform=' . $platformKey);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $platform['name']; ?> - Security Test</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container">
        <!-- Fake URL Bar -->
        <div class="url-bar">
            <i class="fas fa-lock"></i>
            <span class="url-text"><?php echo $platform['url_pattern']; ?></span>
            <span style="margin-left: auto; color: #10b981; font-weight: 600;">
                <i class="fas fa-shield-alt"></i> Secure
            </span>
        </div>
        
        <!-- Platform Header -->
        <div class="login-header">
            <div class="login-logo" style="background-color: <?php echo $platform['color']; ?>20;">
                <img src="<?php echo $platform['logo']; ?>" alt="<?php echo $platform['name']; ?>" style="width: 40px; height: 40px;">
            </div>
            <h1 class="login-title"><?php echo explode(' ', $platform['name'])[0]; ?></h1>
            <p class="login-subtitle">Security Checkpoint</p>
            <p style="color: #6b7280; font-size: 0.9rem; margin-top: 10px;">
                <i class="fas fa-info-circle"></i> Enter test credentials for security audit
            </p>
        </div>
        
        <!-- Login Form -->
        <form method="POST" action="" id="loginForm">
            <input type="hidden" name="csrf_token" value="<?php echo generateCSRFToken(); ?>">
            
            <div class="form-group">
                <label class="form-label" for="username">
                    Email or Phone Number
                    <span style="float: right; color: #3b82f6; font-size: 0.8rem;">
                        <i class="fas fa-user-check"></i> Test Account
                    </span>
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       placeholder="Enter test email or username"
                       value="test_user@example.com"
                       data-tooltip="This is a simulated login. Never enter real credentials on unknown sites."
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">
                    Password
                    <span style="float: right; color: #10b981; font-size: 0.8rem;">
                        <i class="fas fa-lock"></i> Encrypted
                    </span>
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Enter test password"
                       value="testpassword123"
                       data-tooltip="Passwords are masked and stored locally for educational purposes only."
                       required>
                <div class="password-strength">
                    <div class="strength-meter" id="passwordStrength"></div>
                </div>
                <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                    <small style="color: #6b7280;">
                        <i class="fas fa-bolt"></i> Password strength: <span id="strengthText">Strong</span>
                    </small>
                    <small>
                        <a href="#" style="color: #3b82f6; text-decoration: none;">
                            <i class="fas fa-eye"></i> Show
                        </a>
                    </small>
                </div>
            </div>
            
            <div class="form-check">
                <input type="checkbox" id="remember" name="remember" checked>
                <label for="remember" style="color: #6b7280; font-size: 0.9rem;">
                    Keep me logged in
                    <span style="color: #3b82f6; margin-left: 5px;">
                        <i class="fas fa-shield-alt"></i> Recommended
                    </span>
                </label>
            </div>
            
            <button type="submit" class="btn" id="loginButton">
                <i class="fas fa-sign-in-alt"></i> Continue Security Check
            </button>
            
            <div class="security-notice" style="background: #fef3c7; padding: 15px; border-radius: 8px; margin-top: 20px; border-left: 4px solid #f59e0b;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-exclamation-triangle" style="color: #f59e0b;"></i>
                    <strong>Security Alert</strong>
                </div>
                <p style="font-size: 0.9rem; color: #92400e; margin: 0;">
                    This is a simulated login page for educational purposes. No real credentials will be transmitted or stored.
                </p>
            </div>
            
            <div class="login-links" style="margin-top: 25px;">
                <a href="#" style="display: block; margin-bottom: 10px;">
                    <i class="fas fa-key"></i> Forgot password?
                </a>
                <a href="index.php" style="display: block;">
                    <i class="fas fa-arrow-left"></i> Back to Security Tests
                </a>
            </div>
        </form>
    </div>
    
    <script src="script.js"></script>
    <script>
        // Additional login page specific scripts
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.querySelector('a[href="#"]');
            const passwordInput = document.getElementById('password');
            
            if (togglePassword) {
                togglePassword.addEventListener('click', function(e) {
                    e.preventDefault();
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    this.innerHTML = isPassword ? 
                        '<i class="fas fa-eye-slash"></i> Hide' : 
                        '<i class="fas fa-eye"></i> Show';
                });
            }
            
            // Update strength text
            passwordInput.addEventListener('input', function() {
                const strength = Math.min(this.value.length * 10, 100);
                const strengthText = document.getElementById('strengthText');
                
                if (strength < 50) {
                    strengthText.textContent = 'Weak';
                    strengthText.style.color = '#ef4444';
                } else if (strength < 75) {
                    strengthText.textContent = 'Medium';
                    strengthText.style.color = '#f59e0b';
                } else {
                    strengthText.textContent = 'Strong';
                    strengthText.style.color = '#10b981';
                }
            });
            
            // Simulate loading on form submit
            const loginForm = document.getElementById('loginForm');
            const loginButton = document.getElementById('loginButton');
            
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show loading state
                const originalText = loginButton.innerHTML;
                loginButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Verifying credentials...';
                loginButton.disabled = true;
                
                // Simulate network delay
                setTimeout(() => {
                    // Submit the form
                    this.submit();
                }, 1500);
            });
        });
    </script>
</body>
</html>