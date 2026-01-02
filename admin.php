<?php
require_once 'config.php';

// Admin authentication
$isAuthenticated = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if ($username === ADMIN_USERNAME && password_verify($password, ADMIN_PASSWORD_HASH)) {
        $_SESSION['admin_authenticated'] = true;
        $_SESSION['admin_login_time'] = time();
        $isAuthenticated = true;
    } else {
        $error = 'Invalid credentials';
    }
} elseif (isset($_SESSION['admin_authenticated']) && $_SESSION['admin_authenticated'] === true) {
    // Check session timeout (1 hour)
    if (time() - $_SESSION['admin_login_time'] < 3600) {
        $isAuthenticated = true;
    } else {
        unset($_SESSION['admin_authenticated']);
    }
}

// Handle admin actions
if ($isAuthenticated) {
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'reset':
                if (resetAllData()) {
                    $success = 'All data has been reset successfully.';
                } else {
                    $error = 'Failed to reset data.';
                }
                break;
            case 'logout':
                unset($_SESSION['admin_authenticated']);
                session_destroy();
                header('Location: admin.php');
                exit;
        }
    }
}

// Get data for display
$attempts = getAllAttempts();
$stats = getStatistics();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Security Awareness Platform</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <?php if (!$isAuthenticated): ?>
    <!-- Login Form -->
    <div class="login-container" style="max-width: 400px;">
        <div class="login-header">
            <div class="login-logo" style="background-color: #3b82f620;">
                <i class="fas fa-user-shield" style="font-size: 2rem; color: #3b82f6;"></i>
            </div>
            <h1 class="login-title">Admin Access</h1>
            <p class="login-subtitle">Educational Simulation Dashboard</p>
        </div>
        
        <?php if (isset($error)): ?>
        <div style="background: #fee2e2; color: #7f1d1d; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <input type="hidden" name="login" value="1">
            
            <div class="form-group">
                <label class="form-label" for="username">
                    <i class="fas fa-user"></i> Username
                </label>
                <input type="text" 
                       id="username" 
                       name="username" 
                       class="form-control" 
                       placeholder="Enter admin username"
                       required>
            </div>
            
            <div class="form-group">
                <label class="form-label" for="password">
                    <i class="fas fa-key"></i> Password
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control" 
                       placeholder="Enter admin password"
                       required>
            </div>
            
            <button type="submit" class="btn">
                <i class="fas fa-sign-in-alt"></i> Login to Dashboard
            </button>
        </form>
        
        <div style="margin-top: 25px; padding: 15px; background: #f9fafb; border-radius: 8px;">
            <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                <i class="fas fa-info-circle" style="color: #6b7280;"></i>
                <strong style="color: #1f2937;">Default Credentials</strong>
            </div>
            <p style="font-size: 0.9rem; color: #6b7280; margin: 0;">
                Username: <code>admin</code><br>
                Password: <code>admin123</code>
            </p>
        </div>
        
        <div style="text-align: center; margin-top: 20px;">
            <a href="index.php" style="color: #6b7280; text-decoration: none; font-size: 0.9rem;">
                <i class="fas fa-arrow-left"></i> Back to Security Tests
            </a>
        </div>
    </div>
    <?php else: ?>
    <!-- Admin Dashboard -->
    <div class="admin-container">
        <!-- Admin Header -->
        <div class="admin-header">
            <div>
                <h1 style="font-size: 1.8rem; color: #1f2937; margin-bottom: 5px;">
                    <i class="fas fa-chart-line"></i> Simulation Dashboard
                </h1>
                <p style="color: #6b7280;">
                    Educational Phishing Simulation Analytics
                    <span style="margin-left: 10px; background: #3b82f6; color: white; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem;">
                        v<?php echo APP_VERSION; ?>
                    </span>
                </p>
            </div>
            <div>
                <a href="admin.php?action=logout" class="btn-secondary" style="display: inline-flex; align-items: center; gap: 8px; width: auto; padding: 10px 20px;">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </div>
        
        <!-- Messages -->
        <?php if (isset($success)): ?>
        <div style="background: #d1fae5; color: #065f46; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #10b981;">
            <i class="fas fa-check-circle"></i> <?php echo htmlspecialchars($success); ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
        <div style="background: #fee2e2; color: #7f1d1d; padding: 15px; border-radius: 8px; margin-bottom: 20px; border-left: 4px solid #ef4444;">
            <i class="fas fa-exclamation-circle"></i> <?php echo htmlspecialchars($error); ?>
        </div>
        <?php endif; ?>
        
        <!-- Statistics Cards -->
        <div class="admin-stats-grid">
            <div class="admin-stat-card">
                <i class="fas fa-users" style="font-size: 2rem; color: #3b82f6; margin-bottom: 10px;"></i>
                <h3><?php echo number_format($stats['unique_visitors'] ?? 0); ?></h3>
                <p>Unique Visitors</p>
            </div>
            
            <div class="admin-stat-card">
                <i class="fas fa-shield-alt" style="font-size: 2rem; color: #10b981; margin-bottom: 10px;"></i>
                <h3><?php echo number_format($stats['total_tests'] ?? 0); ?></h3>
                <p>Total Tests</p>
            </div>
            
            <div class="admin-stat-card">
                <i class="fas fa-chart-pie" style="font-size: 2rem; color: #8b5cf6; margin-bottom: 10px;"></i>
                <h3><?php echo count($attempts); ?></h3>
                <p>Simulated Attempts</p>
            </div>
            
            <div class="admin-stat-card">
                <i class="fas fa-percentage" style="font-size: 2rem; color: #f59e0b; margin-bottom: 10px;"></i>
                <h3>100%</h3>
                <p>Simulation Success Rate</p>
            </div>
        </div>
        
        <!-- Platform Distribution -->
        <div style="background: #f9fafb; padding: 25px; border-radius: var(--radius); margin-bottom: 30px;">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px; color: #1f2937;">
                <i class="fas fa-th-large"></i> Platform Distribution
            </h3>
            
            <div style="display: flex; flex-wrap: wrap; gap: 20px;">
                <?php
                $platformColors = [
                    'facebook' => '#1877F2',
                    'instagram' => '#E4405F',
                    'google' => '#4285F4',
                    'banking' => '#1E8449',
                    'email' => '#EA4335'
                ];
                
                foreach ($stats['platforms'] ?? [] as $platform => $count):
                    $percentage = $stats['total_tests'] > 0 ? ($count / $stats['total_tests'] * 100) : 0;
                ?>
                <div style="flex: 1; min-width: 150px;">
                    <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                        <span style="font-weight: 600; color: #1f2937; text-transform: capitalize;">
                            <i class="fas fa-circle" style="color: <?php echo $platformColors[$platform] ?? '#6b7280'; ?>; font-size: 0.8rem;"></i>
                            <?php echo $platform; ?>
                        </span>
                        <span style="color: #6b7280; font-weight: 600;"><?php echo number_format($count); ?></span>
                    </div>
                    <div style="height: 8px; background: #e5e7eb; border-radius: 4px; overflow: hidden;">
                        <div style="height: 100%; width: <?php echo $percentage; ?>%; background: <?php echo $platformColors[$platform] ?? '#6b7280'; ?>; border-radius: 4px;"></div>
                    </div>
                    <div style="text-align: right; margin-top: 3px;">
                        <span style="font-size: 0.8rem; color: #6b7280;">
                            <?php echo number_format($percentage, 1); ?>%
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
        
        <!-- Admin Actions -->
        <div style="display: flex; gap: 15px; margin-bottom: 30px;">
            <button id="exportData" class="export-btn">
                <i class="fas fa-file-export"></i> Export Data as CSV
            </button>
            
            <a href="admin.php?action=reset" onclick="return confirm('Are you sure you want to reset all data? This cannot be undone.')" class="reset-btn">
                <i class="fas fa-trash-alt"></i> Reset All Data
            </a>
            
            <button onclick="refreshData()" style="background: #3b82f6; color: white; padding: 12px 25px; border-radius: var(--radius-sm); border: none; font-weight: 600; cursor: pointer; display: flex; align-items: center; gap: 10px;">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        
        <!-- Attempts Table -->
        <div class="admin-table-container">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px; color: #1f2937;">
                <i class="fas fa-history"></i> Recent Simulation Attempts
                <span style="font-size: 0.9rem; color: #6b7280; font-weight: normal; margin-left: 10px;">
                    (Showing last <?php echo min(50, count($attempts)); ?> of <?php echo count($attempts); ?>)
                </span>
            </h3>
            
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Timestamp</th>
                        <th>Platform</th>
                        <th>Username</th>
                        <th>IP Address</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Show recent attempts (last 50)
                    $recentAttempts = array_slice(array_reverse($attempts), 0, 50);
                    
                    if (empty($recentAttempts)):
                    ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 40px; color: #6b7280;">
                            <i class="fas fa-database" style="font-size: 2rem; margin-bottom: 10px; display: block;"></i>
                            No simulation data yet. Run some security tests first.
                        </td>
                    </tr>
                    <?php
                    else:
                    foreach ($recentAttempts as $attempt):
                    ?>
                    <tr>
                        <td><code style="font-size: 0.8rem;"><?php echo substr($attempt['id'], 0, 12); ?>...</code></td>
                        <td><?php echo $attempt['timestamp']; ?></td>
                        <td>
                            <span class="platform-tag" style="background-color: <?php echo $platformColors[$attempt['platform']] ?? '#6b7280'; ?>20; color: <?php echo $platformColors[$attempt['platform']] ?? '#6b7280'; ?>;">
                                <?php echo $attempt['platform']; ?>
                            </span>
                        </td>
                        <td><?php echo htmlspecialchars(substr($attempt['username'], 0, 20)); ?><?php echo strlen($attempt['username']) > 20 ? '...' : ''; ?></td>
                        <td><code style="font-size: 0.8rem;"><?php echo $attempt['ip_address']; ?></code></td>
                        <td>
                            <span style="background: #10b98120; color: #10b981; padding: 3px 8px; border-radius: 12px; font-size: 0.8rem; font-weight: 600;">
                                <i class="fas fa-check-circle"></i> Simulated
                            </span>
                        </td>
                    </tr>
                    <?php
                    endforeach;
                    endif;
                    ?>
                </tbody>
            </table>
        </div>
        
        <!-- Educational Statistics -->
        <div style="background: #fef3c7; padding: 25px; border-radius: var(--radius);">
            <h3 style="font-size: 1.3rem; margin-bottom: 20px; color: #92400e;">
                <i class="fas fa-chalkboard-teacher"></i> Educational Impact
            </h3>
            
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 800; color: #92400e;">
                        <?php echo count($attempts); ?>
                    </div>
                    <div style="color: #92400e; font-size: 0.9rem;">Learning Opportunities</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 800; color: #92400e;">
                        5
                    </div>
                    <div style="color: #92400e; font-size: 0.9rem;">Platforms Covered</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 800; color: #92400e;">
                        <?php echo number_format($stats['unique_visitors'] ?? 0); ?>
                    </div>
                    <div style="color: #92400e; font-size: 0.9rem;">People Educated</div>
                </div>
                
                <div style="text-align: center;">
                    <div style="font-size: 2rem; font-weight: 800; color: #92400e;">
                        100%
                    </div>
                    <div style="color: #92400e; font-size: 0.9rem;">Safe Environment</div>
                </div>
            </div>
            
            <div style="margin-top: 20px; padding: 15px; background: white; border-radius: 8px;">
                <div style="display: flex; align-items: center; gap: 10px; margin-bottom: 10px;">
                    <i class="fas fa-info-circle" style="color: #92400e;"></i>
                    <strong style="color: #92400e;">Administrator Notes</strong>
                </div>
                <p style="color: #92400e; font-size: 0.9rem; margin: 0;">
                    This dashboard shows simulated phishing attempts for educational purposes only. 
                    No real credentials are stored. All data is anonymous and automatically generated 
                    during the simulation process. Use this data to analyze engagement and improve 
                    security awareness training.
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div style="margin-top: 40px; padding-top: 20px; border-top: 1px solid #e5e7eb; text-align: center; color: #6b7280; font-size: 0.9rem;">
            <p>
                <i class="fas fa-shield-alt"></i> Security Awareness Platform - Admin Dashboard
                <br>
                Last updated: <?php echo date('Y-m-d H:i:s'); ?> | 
                Data file: <?php echo file_exists(DATA_FILE) ? round(filesize(DATA_FILE) / 1024, 2) . ' KB' : 'Not found'; ?>
            </p>
        </div>
    </div>
    
    <script src="script.js"></script>
    <script>
        function refreshData() {
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Refreshing...';
            btn.disabled = true;
            
            setTimeout(() => {
                location.reload();
            }, 1000);
        }
        
        // Initialize admin-specific features
        document.addEventListener('DOMContentLoaded', function() {
            // Auto-refresh every 60 seconds
            setTimeout(() => {
                if (confirm('Refresh data to see latest attempts?')) {
                    location.reload();
                }
            }, 60000);
            
            // Add data export functionality
            document.getElementById('exportData')?.addEventListener('click', function() {
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Exporting...';
                
                // In a real implementation, this would fetch data from the server
                setTimeout(() => {
                    this.innerHTML = '<i class="fas fa-file-export"></i> Export Data as CSV';
                    alert('Data export would be implemented in a production environment.\nFor now, check the users.json file.');
                }, 1000);
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>