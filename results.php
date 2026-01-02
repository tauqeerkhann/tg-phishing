<?php
require_once 'config.php';

// Get platform from URL
$platformKey = $_GET['platform'] ?? 'facebook';
$platform = $PLATFORMS[$platformKey] ?? $PLATFORMS['facebook'];

// Get last attempt from session
$lastAttempt = $_SESSION['last_attempt'] ?? [
    'username' => 'test_user',
    'timestamp' => date('Y-m-d H:i:s'),
    'platform' => $platformKey
];

// Simulate captured data (for educational display)
$simulatedData = [
    'username' => $lastAttempt['username'],
    'password' => '••••••••••',
    'ip_address' => $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR'] ?? '192.168.1.100',
    'browser' => $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown Browser',
    'location' => 'Approximated from IP',
    'device' => 'Desktop Computer',
    'login_time' => $lastAttempt['timestamp'],
    'cookies' => 'Session cookies simulated',
    'activity' => 'Recent activity accessed'
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Security Test Results - <?php echo $platform['name']; ?></title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="results-container">
        <!-- Results Header -->
        <div class="results-header">
            <div class="results-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <h1 class="results-title">Phishing Simulation Complete</h1>
            <p class="results-subtitle">
                This is what a real attacker could have accessed
            </p>
            <div style="margin-top: 15px;">
                <span style="background: #fef3c7; color: #92400e; padding: 8px 16px; border-radius: 20px; font-size: 0.9rem;">
                    <i class="fas fa-shield-alt"></i> Educational Simulation
                </span>
            </div>
        </div>
        
        <!-- Simulated Stolen Data -->
        <div class="stolen-data-card">
            <h3>
                <i class="fas fa-database"></i>
                Simulated Data That Could Have Been Stolen
            </h3>
            
            <div class="data-grid">
                <?php foreach ($simulatedData as $key => $value): ?>
                <div class="data-item">
                    <span class="data-label"><?php echo ucwords(str_replace('_', ' ', $key)); ?>:</span>
                    <span class="data-value"><?php echo htmlspecialchars($value); ?></span>
                </div>
                <?php endforeach; ?>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: rgba(239, 68, 68, 0.1); border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 10px; color: #ef4444;">
                    <i class="fas fa-skull-crossbones"></i>
                    <strong>In a real attack, this information could be used for:</strong>
                </div>
                <ul style="margin: 10px 0 0 20px; color: #7f1d1d;">
                    <li>Account takeover</li>
                    <li>Identity theft</li>
                    <li>Financial fraud</li>
                    <li>Further phishing attacks on contacts</li>
                </ul>
            </div>
        </div>
        
        <!-- Red Flags Section -->
        <div>
            <h3 style="font-size: 1.5rem; margin-bottom: 20px; color: #1f2937;">
                <i class="fas fa-flag"></i>
                Red Flags You Should Have Noticed
            </h3>
            
            <div class="red-flags-grid">
                <div class="red-flag-card">
                    <h4><i class="fas fa-globe"></i> URL Inspection</h4>
                    <p>The URL didn't match the official domain. Always check the address bar for authenticity.</p>
                </div>
                
                <div class="red-flag-card">
                    <h4><i class="fas fa-envelope"></i> Unexpected Request</h4>
                    <p>You reached this page from an educational site, not from the actual service.</p>
                </div>
                
                <div class="red-flag-card">
                    <h4><i class="fas fa-certificate"></i> SSL Certificate</h4>
                    <p>While HTTPS was shown, the certificate issuer wasn't verified as legitimate.</p>
                </div>
                
                <div class="red-flag-card">
                    <h4><i class="fas fa-exclamation-circle"></i> Urgency & Threats</h4>
                    <p>Simulated security warnings created false urgency to bypass your caution.</p>
                </div>
            </div>
        </div>
        
        <!-- Protective Measures -->
        <div style="margin: 40px 0; padding: 30px; background: linear-gradient(135deg, #dbeafe, #bfdbfe); border-radius: var(--radius);">
            <h3 style="font-size: 1.5rem; margin-bottom: 20px; color: #1e40af;">
                <i class="fas fa-shield-alt"></i>
                How to Protect Yourself
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px;">
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        1
                    </div>
                    <h4 style="margin-bottom: 10px; color: #1f2937;">Verify URLs</h4>
                    <p style="color: #6b7280; font-size: 0.9rem;">Always check the complete URL in the address bar before entering credentials.</p>
                </div>
                
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        2
                    </div>
                    <h4 style="margin-bottom: 10px; color: #1f2937;">Enable 2FA</h4>
                    <p style="color: #6b7280; font-size: 0.9rem;">Use two-factor authentication on all important accounts.</p>
                </div>
                
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        3
                    </div>
                    <h4 style="margin-bottom: 10px; color: #1f2937;">Use Password Manager</h4>
                    <p style="color: #6b7280; font-size: 0.9rem;">Password managers won't auto-fill on fake sites.</p>
                </div>
                
                <div style="background: white; padding: 20px; border-radius: 8px;">
                    <div style="width: 40px; height: 40px; background: #3b82f6; color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                        4
                    </div>
                    <h4 style="margin-bottom: 10px; color: #1f2937;">Verify Sender Emails</h4>
                    <p style="color: #6b7280; font-size: 0.9rem;">Check the complete email address of security alerts.</p>
                </div>
            </div>
        </div>
        
        <!-- Statistics -->
        <div style="background: #f9fafb; padding: 25px; border-radius: var(--radius); margin-bottom: 30px;">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px; color: #1f2937;">
                <i class="fas fa-chart-bar"></i>
                Simulation Statistics
            </h3>
            
            <div style="display: flex; justify-content: space-around; flex-wrap: wrap; gap: 20px;">
                <?php
                $stats = getStatistics();
                $platformStats = $stats['platforms'] ?? [];
                ?>
                
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #3b82f6;">
                        <?php echo number_format($stats['total_tests'] ?? 0); ?>
                    </div>
                    <div style="color: #6b7280; font-size: 0.9rem;">Total Tests Conducted</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #10b981;">
                        <?php echo number_format($stats['unique_visitors'] ?? 0); ?>
                    </div>
                    <div style="color: #6b7280; font-size: 0.9rem;">People Educated</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 2.5rem; font-weight: 800; color: #8b5cf6;">
                        <?php echo max($platformStats) ?? 0; ?>
                    </div>
                    <div style="color: #6b7280; font-size: 0.9rem;">Most Popular Test</div>
                </div>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div style="display: flex; gap: 15px; flex-wrap: wrap; margin-top: 30px;">
            <a href="index.php" class="btn" style="flex: 1; min-width: 200px; text-decoration: none; text-align: center;">
                <i class="fas fa-home"></i> Back to Security Tests
            </a>
            
            <a href="login.php?platform=<?php echo $platformKey; ?>" class="btn" style="flex: 1; min-width: 200px; text-decoration: none; text-align: center; background: #7c3aed;">
                <i class="fas fa-redo"></i> Try Another Platform
            </a>
            
            <button onclick="shareResults()" class="btn" style="flex: 1; min-width: 200px; background: #10b981;">
                <i class="fas fa-share-alt"></i> Share Awareness
            </button>
        </div>
        
        <!-- Final Disclaimer -->
        <div style="margin-top: 40px; padding: 20px; background: #fef3c7; border-radius: var(--radius); border-left: 6px solid #f59e0b;">
            <div style="display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-graduation-cap" style="font-size: 2rem; color: #f59e0b;"></i>
                <div>
                    <h4 style="margin-bottom: 10px; color: #92400e;">Educational Purpose Achieved!</h4>
                    <p style="color: #92400e; margin: 0;">
                        <strong>Remember:</strong> This was a controlled simulation. No real data was compromised. 
                        The goal was to demonstrate how phishing works and help you recognize real threats. 
                        Always verify website authenticity before entering sensitive information.
                    </p>
                </div>
            </div>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        function shareResults() {
            const text = "I just completed a phishing awareness simulation and learned how to spot fake login pages! Try it yourself to improve your cybersecurity skills.";
            
            if (navigator.share) {
                navigator.share({
                    title: 'Phishing Awareness Test',
                    text: text,
                    url: window.location.origin
                });
            } else {
                // Fallback for browsers that don't support Web Share API
                alert('Share this message:\n\n' + text + '\n\n' + window.location.origin);
            }
        }
        
        // Animate data items
        document.addEventListener('DOMContentLoaded', function() {
            const dataItems = document.querySelectorAll('.data-item');
            dataItems.forEach((item, index) => {
                item.style.opacity = '0';
                item.style.transform = 'translateX(-20px)';
                
                setTimeout(() => {
                    item.style.transition = 'opacity 0.5s ease, transform 0.5s ease';
                    item.style.opacity = '1';
                    item.style.transform = 'translateX(0)';
                }, index * 100);
            });
            
            // Add copy protection
            document.addEventListener('copy', function(e) {
                const selection = window.getSelection().toString();
                if (selection.includes('test_user') || selection.includes('192.168')) {
                    e.preventDefault();
                    alert('This is simulated data for educational purposes only.');
                }
            });
        });
    </script>
</body>
</html>