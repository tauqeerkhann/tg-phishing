/**
 * Educational Phishing Simulation - Interactive Scripts
 * FOR DEMONSTRATION PURPOSES ONLY
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    initTooltips();
    
    // Initialize password strength meter on login page
    initPasswordStrengthMeter();
    
    // Initialize URL spoofing simulation
    initURLSpoofing();
    
    // Initialize security animations
    initSecurityAnimations();
    
    // Initialize admin functionality
    initAdminFeatures();
});

/**
 * Initialize tooltips for educational elements
 */
function initTooltips() {
    const tooltips = document.querySelectorAll('[data-tooltip]');
    
    tooltips.forEach(element => {
        element.addEventListener('mouseenter', function(e) {
            const tooltipText = this.getAttribute('data-tooltip');
            const tooltip = document.createElement('div');
            tooltip.className = 'tooltip';
            tooltip.textContent = tooltipText;
            tooltip.style.position = 'absolute';
            tooltip.style.background = 'rgba(0,0,0,0.8)';
            tooltip.style.color = 'white';
            tooltip.style.padding = '8px 12px';
            tooltip.style.borderRadius = '4px';
            tooltip.style.fontSize = '12px';
            tooltip.style.zIndex = '1000';
            tooltip.style.maxWidth = '250px';
            
            document.body.appendChild(tooltip);
            
            const rect = this.getBoundingClientRect();
            tooltip.style.left = rect.left + 'px';
            tooltip.style.top = (rect.top - tooltip.offsetHeight - 10) + 'px';
            
            this._tooltip = tooltip;
        });
        
        element.addEventListener('mouseleave', function() {
            if (this._tooltip) {
                this._tooltip.remove();
            }
        });
    });
}

/**
 * Initialize password strength meter simulation
 */
function initPasswordStrengthMeter() {
    const passwordInput = document.getElementById('password');
    if (!passwordInput) return;
    
    const strengthMeter = document.querySelector('.strength-meter');
    if (!strengthMeter) return;
    
    passwordInput.addEventListener('input', function() {
        const password = this.value;
        let strength = 0;
        
        // Simulate strength calculation
        if (password.length > 0) strength = 25;
        if (password.length >= 6) strength = 50;
        if (password.length >= 8 && /[A-Z]/.test(password)) strength = 75;
        if (password.length >= 10 && /[A-Z]/.test(password) && /[0-9]/.test(password) && /[^A-Za-z0-9]/.test(password)) {
            strength = 100;
        }
        
        strengthMeter.style.width = strength + '%';
        
        // Change color based on strength
        if (strength < 50) {
            strengthMeter.style.backgroundColor = '#ef4444'; // Red
        } else if (strength < 75) {
            strengthMeter.style.backgroundColor = '#f59e0b'; // Orange
        } else {
            strengthMeter.style.backgroundColor = '#10b981'; // Green
        }
    });
}

/**
 * Initialize URL spoofing simulation
 */
function initURLSpoofing() {
    const urlBar = document.querySelector('.url-bar');
    if (!urlBar) return;
    
    // Get current platform from URL or default to facebook
    const urlParams = new URLSearchParams(window.location.search);
    const platform = urlParams.get('platform') || 'facebook';
    
    // Platform URL patterns
    const urlPatterns = {
        facebook: 'https://facebook.com/security-check',
        instagram: 'https://instagram.com/accounts/login',
        google: 'https://accounts.google.com/signin',
        banking: 'https://secure.bank.example.com/login',
        email: 'https://mail.example.com/login'
    };
    
    // Update URL bar if it exists
    if (urlBar) {
        const urlText = urlBar.querySelector('.url-text') || urlBar;
        urlText.textContent = urlPatterns[platform] || urlPatterns.facebook;
        
        // Add SSL icon
        if (!urlBar.querySelector('.ssl-icon')) {
            const sslIcon = document.createElement('i');
            sslIcon.className = 'fas fa-lock ssl-icon';
            sslIcon.style.color = '#10b981';
            urlBar.prepend(sslIcon);
        }
    }
}

/**
 * Initialize security animations and effects
 */
function initSecurityAnimations() {
    // Animate stats counter on landing page
    const statNumbers = document.querySelectorAll('.stat-item h3');
    statNumbers.forEach(stat => {
        const target = parseInt(stat.textContent.replace(/,/g, ''));
        if (!isNaN(target) && target > 0) {
            animateCounter(stat, 0, target, 2000);
        }
    });
    
    // Add hover effects to platform cards
    const platformCards = document.querySelectorAll('.platform-card');
    platformCards.forEach(card => {
        card.addEventListener('mouseenter', () => {
            card.style.transform = 'translateY(-10px) scale(1.02)';
        });
        
        card.addEventListener('mouseleave', () => {
            card.style.transform = 'translateY(0) scale(1)';
        });
    });
    
    // Add typing effect to educational messages
    const typingElements = document.querySelectorAll('[data-typing-effect]');
    typingElements.forEach(element => {
        const text = element.textContent;
        element.textContent = '';
        typeText(element, text, 50);
    });
}

/**
 * Animate counter from start to end
 */
function animateCounter(element, start, end, duration) {
    let startTimestamp = null;
    const step = (timestamp) => {
        if (!startTimestamp) startTimestamp = timestamp;
        const progress = Math.min((timestamp - startTimestamp) / duration, 1);
        const value = Math.floor(progress * (end - start) + start);
        element.textContent = value.toLocaleString();
        if (progress < 1) {
            window.requestAnimationFrame(step);
        }
    };
    window.requestAnimationFrame(step);
}

/**
 * Type text with typing effect
 */
function typeText(element, text, speed) {
    let i = 0;
    function type() {
        if (i < text.length) {
            element.textContent += text.charAt(i);
            i++;
            setTimeout(type, speed);
        }
    }
    type();
}

/**
 * Initialize admin dashboard features
 */
function initAdminFeatures() {
    // Export data as CSV
    const exportBtn = document.getElementById('exportData');
    if (exportBtn) {
        exportBtn.addEventListener('click', function() {
            // This would normally fetch data from the server
            // For demo, we'll create sample CSV
            const csvContent = "data:text/csv;charset=utf-8," 
                + "ID,Timestamp,Platform,Username,Password,IP Address,User Agent\n"
                + "attempt_1,2024-01-02 10:30:00,facebook,john_doe,********,127.0.0.1,Mozilla/5.0\n"
                + "attempt_2,2024-01-02 11:15:00,instagram,jane_smith,********,127.0.0.1,Mozilla/5.0\n";
            
            const encodedUri = encodeURI(csvContent);
            const link = document.createElement("a");
            link.setAttribute("href", encodedUri);
            link.setAttribute("download", "security_test_data.csv");
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Show success message
            showNotification('Data exported successfully!', 'success');
        });
    }
    
    // Reset data confirmation
    const resetBtn = document.getElementById('resetData');
    if (resetBtn) {
        resetBtn.addEventListener('click', function() {
            if (confirm('Are you sure you want to reset all data? This action cannot be undone.')) {
                // This would normally call a server endpoint
                // For demo, we'll just show a message
                showNotification('All data has been reset successfully!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            }
        });
    }
}

/**
 * Show notification message
 */
function showNotification(message, type = 'info') {
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <i class="fas fa-${type === 'success' ? 'check-circle' : 'info-circle'}"></i>
        <span>${message}</span>
        <button class="notification-close">&times;</button>
    `;
    
    notification.style.position = 'fixed';
    notification.style.top = '20px';
    notification.style.right = '20px';
    notification.style.padding = '15px 20px';
    notification.style.background = type === 'success' ? '#10b981' : '#3b82f6';
    notification.style.color = 'white';
    notification.style.borderRadius = '8px';
    notification.style.boxShadow = '0 5px 15px rgba(0,0,0,0.2)';
    notification.style.zIndex = '9999';
    notification.style.display = 'flex';
    notification.style.alignItems = 'center';
    notification.style.gap = '10px';
    notification.style.minWidth = '300px';
    
    document.body.appendChild(notification);
    
    // Auto-remove after 5 seconds
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 300);
    }, 5000);
    
    // Close button
    notification.querySelector('.notification-close').addEventListener('click', () => {
        notification.remove();
    });
}

/**
 * Simulate OTP verification
 */
function simulateOTPVerification() {
    const otpInputs = document.querySelectorAll('.otp-input');
    if (otpInputs.length === 0) return;
    
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', function() {
            if (this.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            
            // Check if all inputs are filled
            const allFilled = Array.from(otpInputs).every(input => input.value.length === 1);
            if (allFilled) {
                // Simulate verification
                setTimeout(() => {
                    document.querySelector('.verification-status').innerHTML = 
                        '<i class="fas fa-check-circle"></i> Verified successfully!';
                    document.querySelector('.verification-status').style.color = '#10b981';
                    
                    // Auto-proceed after 1 second
                    setTimeout(() => {
                        window.location.href = 'results.php';
                    }, 1000);
                }, 500);
            }
        });
        
        // Allow backspace to move to previous input
        input.addEventListener('keydown', function(e) {
            if (e.key === 'Backspace' && this.value.length === 0 && index > 0) {
                otpInputs[index - 1].focus();
            }
        });
    });
}

// Export functions for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initTooltips,
        initPasswordStrengthMeter,
        initURLSpoofing,
        initSecurityAnimations,
        showNotification
    };
}