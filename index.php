<?php
require_once 'config.php';

// Get statistics for display
$stats = getStatistics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo APP_NAME; ?> - Security Awareness Platform</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <div class="container">
        <!-- Header with Security Badge -->
        <header class="header">
            <div class="header-content">
                <div class="logo-section">
                    <i class="fas fa-shield-alt logo-icon"></i>
                    <h1 class="logo-text">Security<span class="logo-highlight">Aware</span></h1>
                </div>
                <div class="security-badge">
                    <i class="fas fa-lock"></i>
                    <span>Secure Test Environment</span>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="main-content">
            <!-- Hero Section -->
            <section class="hero-section">
                <div class="hero-content">
                    <h2 class="hero-title">Social Media Security Test</h2>
                    <p class="hero-subtitle">Learn how phishing attacks work in a safe, controlled environment</p>
                    
                    <div class="stats-card">
                        <div class="stat-item">
                            <i class="fas fa-users"></i>
                            <div>
                                <h3><?php echo number_format($stats['unique_visitors'] ?? 0); ?></h3>
                                <p>Visitors Educated</p>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-shield-check"></i>
                            <div>
                                <h3><?php echo number_format($stats['total_tests'] ?? 0); ?></h3>
                                <p>Security Tests Completed</p>
                            </div>
                        </div>
                        <div class="stat-item">
                            <i class="fas fa-bug"></i>
                            <div>
                                <h3>100%</h3>
                                <p>Simulation Success Rate</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Platform Selection -->
            <section class="platform-section">
                <h3 class="section-title">Choose a Security Test</h3>
                <p class="section-subtitle">Select a platform to learn how phishing attacks target different services</p>
                
                <div class="platform-grid">
                    <?php foreach ($PLATFORMS as $key => $platform): ?>
                    <a href="login.php?platform=<?php echo $key; ?>" class="platform-card">
                        <div class="platform-icon" style="background-color: <?php echo $platform['color']; ?>20;">
                            <img src="<?php echo $platform['logo']; ?>" alt="<?php echo $platform['name']; ?>" class="platform-logo">
                        </div>
                        <h4><?php echo $platform['name']; ?></h4>
                        <p><?php echo $platform['description']; ?></p>
                        <div class="platform-stats">
                            <span class="stat-badge">
                                <i class="fas fa-chart-line"></i>
                                <?php echo number_format($stats['platforms'][$key] ?? 0); ?> tests
                            </span>
                        </div>
                        <div class="start-test">
                            Start Test <i class="fas fa-arrow-right"></i>
                        </div>
                    </a>
                    <?php endforeach; ?>
                </div>
            </section>

            <!-- Educational Disclaimer -->
            <section class="disclaimer-section">
                <div class="disclaimer-card">
                    <div class="disclaimer-header">
                        <i class="fas fa-exclamation-triangle"></i>
                        <h4>Important Disclaimer</h4>
                    </div>
                    <div class="disclaimer-content">
                        <p><strong>This is an educational simulation only.</strong> No real credentials are stored or transmitted. This platform demonstrates how phishing attacks work to help you recognize and avoid real threats.</p>
                        <ul class="disclaimer-list">
                            <li><i class="fas fa-check-circle"></i> No real login credentials are captured</li>
                            <li><i class="fas fa-check-circle"></i> All data is stored locally and anonymously</li>
                            <li><i class="fas fa-check-circle"></i> For educational and awareness purposes only</li>
                            <li><i class="fas fa-check-circle"></i> Never enter real passwords on unknown sites</li>
                        </ul>
                    </div>
                </div>
            </section>

            <!-- Security Tips -->
            <section class="tips-section">
                <h3 class="section-title">Phishing Awareness Tips</h3>
                <div class="tips-grid">
                    <?php foreach ($SECURITY_TIPS as $index => $tip): ?>
                    <div class="tip-card">
                        <div class="tip-number"><?php echo $index + 1; ?></div>
                        <p><?php echo $tip; ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            </section>
        </main>

        <!-- Footer -->
        <footer class="footer">
            <div class="footer-content">
                <div class="footer-section">
                    <h5><i class="fas fa-shield-alt"></i> Security Awareness Test</h5>
                    <p>Educational platform for phishing awareness</p>
                    <p class="footer-version">v<?php echo APP_VERSION; ?></p>
                </div>
                <div class="footer-section">
                    <h5>For Educational Use Only</h5>
                    <p>This simulation is designed to teach cybersecurity awareness. Always verify website authenticity before entering credentials.</p>
                </div>
                <div class="footer-section">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="#"><i class="fas fa-user-shield"></i> Admin Dashboard</a></li>
                        <li><a href="#"><i class="fas fa-file-alt"></i> Privacy Policy</a></li>
                        <li><a href="#"><i class="fas fa-question-circle"></i> FAQ</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> Security Awareness Platform. This is a simulated environment for educational purposes.</p>
            </div>
        </footer>
    </div>

    <script src="script.js"></script>
</body>
</html>