<?php
require_once 'config.php';

// Get platform from URL
$platformKey = $_GET['platform'] ?? 'facebook';
$platform = $PLATFORMS[$platformKey] ?? $PLATFORMS['facebook'];

// Handle verification submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Redirect to results page
    header('Location: results.php?platform=' . $platformKey);
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Required - <?php echo $platform['name']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="login-container" style="max-width: 450px;">
        <!-- Platform Header -->
        <div class="login-header">
            <div class="login-logo" style="background-color: <?php echo $platform['color']; ?>20;">
                <img src="<?php echo $platform['logo']; ?>" alt="<?php echo $platform['name']; ?>" style="width: 40px; height: 40px;">
            </div>
            <h1 class="login-title">Verify Your Identity</h1>
            <p class="login-subtitle">Additional security check required</p>
            
            <div style="display: flex; align-items: center; gap: 10px; margin-top: 15px; padding: 10px 15px; background: #dbeafe; border-radius: 8px;">
                <i class="fas fa-shield-alt" style="color: #3b82f6;"></i>
                <span style="font-size: 0.9rem; color: #1e40af;">
                    This is a simulated 2FA process for educational purposes
                </span>
            </div>
        </div>
        
        <!-- Progress Steps -->
        <div style="display: flex; justify-content: space-between; margin-bottom: 30px; position: relative;">
            <div style="position: absolute; top: 15px; left: 0; right: 0; height: 2px; background: #e5e7eb; z-index: 1;"></div>
            
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2;">
                <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check"></i>
                </div>
                <span style="font-size: 0.8rem; margin-top: 5px;">Login</span>
            </div>
            
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2;">
                <div style="width: 30px; height: 30px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    2
                </div>
                <span style="font-size: 0.8rem; margin-top: 5px; font-weight: 600;">Verification</span>
            </div>
            
            <div style="display: flex; flex-direction: column; align-items: center; z-index: 2;">
                <div style="width: 30px; height: 30px; background: #e5e7eb; color: #6b7280; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                    3
                </div>
                <span style="font-size: 0.8rem; margin-top: 5px;">Results</span>
            </div>
        </div>
        
        <!-- Verification Methods -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 1.1rem; margin-bottom: 15px; color: #1f2937;">
                <i class="fas fa-mobile-alt"></i> Choose verification method:
            </h3>
            
            <div style="display: grid; gap: 10px;">
                <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    <input type="radio" name="method" value="sms" checked style="margin-right: 10px;">
                    <div>
                        <strong>Text message (SMS)</strong>
                        <p style="font-size: 0.9rem; color: #6b7280; margin: 5px 0 0 0;">
                            Send code to phone ending in ••••1234
                        </p>
                    </div>
                </label>
                
                <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    <input type="radio" name="method" value="authenticator" style="margin-right: 10px;">
                    <div>
                        <strong>Authenticator app</strong>
                        <p style="font-size: 0.9rem; color: #6b7280; margin: 5px 0 0 0;">
                            Use Google Authenticator or similar
                        </p>
                    </div>
                </label>
                
                <label style="display: flex; align-items: center; padding: 15px; border: 2px solid #e5e7eb; border-radius: 8px; cursor: pointer;">
                    <input type="radio" name="method" value="backup" style="margin-right: 10px;">
                    <div>
                        <strong>Backup code</strong>
                        <p style="font-size: 0.9rem; color: #6b7280; margin: 5px 0 0 0;">
                            Use one of your 10 backup codes
                        </p>
                    </div>
                </label>
            </div>
        </div>
        
        <!-- OTP Input -->
        <div style="margin-bottom: 30px;">
            <label style="display: block; margin-bottom: 10px; font-weight: 500; color: #1f2937;">
                <i class="fas fa-key"></i> Enter 6-digit verification code
            </label>
            
            <div style="display: flex; gap: 10px; justify-content: center;">
                <?php for ($i = 1; $i <= 6; $i++): ?>
                <input type="text" 
                       maxlength="1" 
                       class="otp-input"
                       style="width: 50px; height: 60px; text-align: center; font-size: 1.5rem; border: 2px solid #e5e7eb; border-radius: 8px;"
                       oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                <?php endfor; ?>
            </div>
            
            <div style="text-align: center; margin-top: 15px;">
                <button type="button" id="sendCode" style="background: none; border: none; color: #3b82f6; cursor: pointer; font-size: 0.9rem;">
                    <i class="fas fa-redo"></i> Send new code
                </button>
                <div style="font-size: 0.8rem; color: #6b7280; margin-top: 5px;">
                    Code expires in <span id="timer">02:00</span>
                </div>
            </div>
        </div>
        
        <!-- Security Questions Simulation -->
        <div style="margin-bottom: 30px;">
            <h3 style="font-size: 1.1rem; margin-bottom: 15px; color: #1f2937;">
                <i class="fas fa-question-circle"></i> Security question
            </h3>
            
            <div class="form-group">
                <label class="form-label">What was the name of your first pet?</label>
                <input type="text" class="form-control" placeholder="Enter your answer" value="Fluffy">
            </div>
        </div>
        
        <!-- CAPTCHA Simulation -->
        <div style="margin-bottom: 30px; padding: 20px; background: #f9fafb; border-radius: 8px;">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                <div>
                    <strong style="color: #1f2937;">Confirm you're not a robot</strong>
                    <p style="font-size: 0.9rem; color: #6b7280; margin: 5px 0 0 0;">
                        This helps prevent automated attacks
                    </p>
                </div>
                <div style="width: 40px; height: 40px; background: #10b981; border-radius: 8px; display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-check" style="color: white;"></i>
                </div>
            </div>
            
            <div style="display: flex; align-items: center; gap: 10px; padding: 15px; background: white; border-radius: 8px;">
                <input type="checkbox" id="captcha" checked disabled style="width: 20px; height: 20px;">
                <label for="captcha" style="font-size: 0.9rem; color: #1f2937;">
                    I'm not a robot
                </label>
                <div style="margin-left: auto; display: flex; align-items: center; gap: 5px;">
                    <img src="https://img.icons8.com/color/48/000000/google-logo.png" style="width: 20px; height: 20px;">
                    <span style="font-size: 0.8rem; color: #6b7280;">reCAPTCHA</span>
                </div>
            </div>
        </div>
        
        <!-- Verification Status -->
        <div class="verification-status" style="text-align: center; padding: 20px; background: #d1fae5; border-radius: 8px; margin-bottom: 20px; display: none;">
            <i class="fas fa-spinner fa-spin"></i>
            <span>Verifying your identity...</span>
        </div>
        
        <!-- Submit Button -->
        <form method="POST" action="" id="verifyForm">
            <button type="submit" class="btn" id="verifyButton">
                <i class="fas fa-check-circle"></i> Complete Verification
            </button>
        </form>
        
        <!-- Educational Note -->
        <div style="margin-top: 25px; padding: 15px; background: #fef3c7; border-radius: 8px; border-left: 4px solid #f59e0b;">
            <div style="display: flex; align-items: flex-start; gap: 10px;">
                <i class="fas fa-lightbulb" style="color: #f59e0b; margin-top: 2px;"></i>
                <div>
                    <strong style="display: block; margin-bottom: 5px; color: #92400e;">Phishing Awareness Tip</strong>
                    <p style="font-size: 0.9rem; color: #92400e; margin: 0;">
                        Legitimate services won't ask for verification codes unless you initiated the login. 
                        Never share verification codes with anyone.
                    </p>
                </div>
            </div>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> Cancel and return to security tests
            </a>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize OTP verification
            const otpInputs = document.querySelectorAll('.otp-input');
            let currentTimer = 120; // 2 minutes in seconds
            
            // OTP input handling
            otpInputs.forEach((input, index) => {
                input.addEventListener('input', function() {
                    if (this.value.length === 1 && index < otpInputs.length - 1) {
                        otpInputs[index + 1].focus();
                    }
                    
                    // Auto-fill with demo code when all inputs have focus
                    const allEmpty = Array.from(otpInputs).every(inp => inp.value === '');
                    if (allEmpty && index === 0) {
                        // Auto-fill with demo code
                        setTimeout(() => {
                            const demoCode = '123456';
                            otpInputs.forEach((inp, idx) => {
                                inp.value = demoCode[idx];
                            });
                        }, 500);
                    }
                });
                
                input.addEventListener('keydown', function(e) {
                    if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                        otpInputs[index - 1].focus();
                    }
                });
            });
            
            // Timer countdown
            function updateTimer() {
                const minutes = Math.floor(currentTimer / 60);
                const seconds = currentTimer % 60;
                document.getElementById('timer').textContent = 
                    `${minutes.toString().padStart(2, '0')}:${seconds.toString().padStart(2, '0')}`;
                
                if (currentTimer > 0) {
                    currentTimer--;
                    setTimeout(updateTimer, 1000);
                } else {
                    document.getElementById('timer').style.color = '#ef4444';
                    document.getElementById('timer').textContent = 'Expired';
                }
            }
            
            // Start timer
            updateTimer();
            
            // Resend code button
            document.getElementById('sendCode').addEventListener('click', function() {
                currentTimer = 120;
                document.getElementById('timer').style.color = '';
                updateTimer();
                
                // Show notification
                const notification = document.createElement('div');
                notification.style.cssText = `
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    background: #10b981;
                    color: white;
                    padding: 10px 20px;
                    border-radius: 8px;
                    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
                    z-index: 1000;
                `;
                notification.innerHTML = '<i class="fas fa-check-circle"></i> New code sent to your device';
                document.body.appendChild(notification);
                
                setTimeout(() => {
                    notification.style.opacity = '0';
                    notification.style.transform = 'translateX(100%)';
                    setTimeout(() => notification.remove(), 300);
                }, 3000);
            });
            
            // Form submission
            document.getElementById('verifyForm').addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Show verification status
                const statusDiv = document.querySelector('.verification-status');
                const verifyButton = document.getElementById('verifyButton');
                
                statusDiv.style.display = 'block';
                verifyButton.disabled = true;
                verifyButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';
                
                // Simulate verification delay
                setTimeout(() => {
                    // Submit the form
                    this.submit();
                }, 2000);
            });
            
            // Auto-submit after OTP is entered
            otpInputs[5].addEventListener('input', function() {
                if (this.value.length === 1) {
                    // All OTP fields are filled
                    const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
                    if (allFilled) {
                        setTimeout(() => {
                            document.getElementById('verifyForm').submit();
                        }, 500);
                    }
                }
            });
        });
    </script>
</body>
</html>