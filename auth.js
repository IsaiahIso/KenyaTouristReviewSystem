 // auth.js - Authentication JavaScript

// ========== MODAL FUNCTIONS ==========
function openAuthModal() {
    document.getElementById('authModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    document.documentElement.style.overflow = 'hidden';
    openTab(null, 'loginTab');
}

function closeAuthModal() {
    document.getElementById('authModal').style.display = 'none';
    document.body.style.overflow = 'auto';
    document.documentElement.style.overflow = 'auto';
}

// Close modal when clicking outside
window.addEventListener('click', function(event) {
    const modal = document.getElementById('authModal');
    if (event.target === modal) {
        closeAuthModal();
    }
});

// Close modal with Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closeAuthModal();
    }
});

// ========== TAB FUNCTIONS ==========
function openTab(evt, tabName) {
    // Hide all tab contents
    const tabContents = document.getElementsByClassName('tab-content');
    for (let i = 0; i < tabContents.length; i++) {
        tabContents[i].classList.remove('active');
    }
    
    // Remove active class from all tab buttons
    const tabButtons = document.getElementsByClassName('tab-btn');
    for (let i = 0; i < tabButtons.length; i++) {
        tabButtons[i].classList.remove('active');
    }
    
    // Show current tab and activate button
    document.getElementById(tabName).classList.add('active');
    
    if (evt) {
        evt.currentTarget.classList.add('active');
    } else {
        // If called without event, activate correct button
        const buttons = document.querySelectorAll('.tab-btn');
        buttons.forEach(btn => {
            if (btn.textContent.includes(tabName === 'loginTab' ? 'Sign In' : 'Sign Up')) {
                btn.classList.add('active');
            }
        });
    }
}

function switchToSignup() {
    openTab(null, 'signupTab');
}

function switchToLogin() {
    openTab(null, 'loginTab');
}

// ========== PASSWORD FUNCTIONS ==========
function togglePassword(inputId, checkbox) {
    const passwordInput = document.getElementById(inputId);
    if (passwordInput) {
        passwordInput.type = checkbox.checked ? 'text' : 'password';
    }
}

// Password strength checker
document.addEventListener('DOMContentLoaded', function() {
    const passwordInput = document.getElementById('signupPassword');
    if (passwordInput) {
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.querySelector('.strength-bar');
            const strengthText = document.getElementById('passwordStrength');
            
            if (!strengthBar || !strengthText) return;
            
            let strength = 0;
            let color = '#e74c3c';
            let text = 'Weak';
            
            // Check password strength
            if (password.length >= 6) strength += 25;
            if (password.match(/[a-z]/) && password.match(/[A-Z]/)) strength += 25;
            if (password.match(/\d/)) strength += 25;
            if (password.match(/[^a-zA-Z\d]/)) strength += 25;
            
            // Update UI
            strengthBar.style.width = strength + '%';
            
            if (strength >= 75) {
                color = '#27ae60';
                text = 'Strong';
            } else if (strength >= 50) {
                color = '#f39c12';
                text = 'Medium';
            }
            
            strengthBar.style.backgroundColor = color;
            strengthText.textContent = text;
            strengthText.style.color = color;
        });
    }
});

// ========== FORM VALIDATION ==========
function validateSignupForm() {
    let isValid = true;
    const errors = [];
    
    // Username validation
    const username = document.getElementById('signupUsername').value.trim();
    if (username.length < 3 || username.length > 50) {
        errors.push('Username must be 3-50 characters');
        isValid = false;
    }
    
    // Email validation
    const email = document.getElementById('signupEmail').value.trim();
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
        errors.push('Please enter a valid email address');
        isValid = false;
    }
    
    // Password validation
    const password = document.getElementById('signupPassword').value;
    if (password.length < 6) {
        errors.push('Password must be at least 6 characters');
        isValid = false;
    }
    
    // Confirm password
    const confirmPassword = document.getElementById('signupConfirmPassword').value;
    if (password !== confirmPassword) {
        errors.push('Passwords do not match');
        isValid = false;
    }
    
    return { isValid, errors };
}

// ========== AUTH FUNCTIONS ==========
async function handleLogin(event) {
    event.preventDefault();
    
    const username = document.getElementById('loginUsername').value.trim();
    const password = document.getElementById('loginPassword').value;
    
    if (!username || !password) {
        showMessage('loginTab', 'Please fill in all fields', 'error');
        return;
    }
    
    const submitBtn = event.target.querySelector('.auth-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Signing in...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('login.php', {
            method: 'POST',
            body: new FormData(event.target)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('loginTab', data.message, 'success');
            
            // Store in sessionStorage
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('username', data.user.username);
            sessionStorage.setItem('userRole', data.user.role);
            sessionStorage.setItem('userId', data.user.id);
            
            setTimeout(() => {
                closeAuthModal();
                updateUserUI(true, data.user);
                
                if (data.user.role === 'admin' && data.redirect) {
                    window.location.href = data.redirect;
                }
            }, 1500);
            
        } else {
            showMessage('loginTab', data.message, 'error');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
        
    } catch (error) {
        showMessage('loginTab', 'Network error. Please try again.', 'error');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        console.error('Login error:', error);
    }
}

async function handleSignup(event) {
    event.preventDefault();
    
    const validation = validateSignupForm();
    if (!validation.isValid) {
        showMessage('signupTab', validation.errors.join(', '), 'error');
        return;
    }
    
    const submitBtn = event.target.querySelector('.auth-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Creating account...';
    submitBtn.disabled = true;
    
    try {
        const response = await fetch('register.php', {
            method: 'POST',
            body: new FormData(event.target)
        });
        
        const data = await response.json();
        
        if (data.success) {
            showMessage('signupTab', data.message, 'success');
            
            // Store in sessionStorage
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('username', data.user.username);
            sessionStorage.setItem('userRole', data.user.role);
            sessionStorage.setItem('userId', data.user.id);
            
            setTimeout(() => {
                closeAuthModal();
                updateUserUI(true, data.user);
            }, 1500);
            
        } else {
            showMessage('signupTab', data.message, 'error');
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        }
        
    } catch (error) {
        showMessage('signupTab', 'Network error. Please try again.', 'error');
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        console.error('Registration error:', error);
    }
}

async function handleLogout() {
    try {
        const response = await fetch('logout.php');
        const data = await response.json();
        
        if (data.success) {
            // Clear sessionStorage
            sessionStorage.clear();
            
            // Update UI
            updateUserUI(false);
            
            // Redirect to home
            window.location.href = 'index.php';
        }
    } catch (error) {
        console.error('Logout error:', error);
        // Still clear storage on error
        sessionStorage.clear();
        window.location.href = 'index.php';
    }
}

// ========== DROPDOWN FUNCTIONS ==========
function initUserDropdown() {
    const userMenu = document.querySelector('.user-menu');
    if (!userMenu) return;
    
    const dropdown = userMenu.querySelector('.user-dropdown');
    const trigger = userMenu.querySelector('.user-trigger');
    
    if (!dropdown || !trigger) return;
    
    let hideTimeout;
    let showTimeout;
    
    // Show dropdown with delay
    trigger.addEventListener('mouseenter', () => {
        clearTimeout(hideTimeout);
        showTimeout = setTimeout(() => {
            dropdown.classList.add('show');
        }, 100);
    });
    
    trigger.addEventListener('click', (e) => {
        e.preventDefault();
        clearTimeout(hideTimeout);
        clearTimeout(showTimeout);
        dropdown.classList.toggle('show');
    });
    
    // Keep dropdown open when hovering over it
    dropdown.addEventListener('mouseenter', () => {
        clearTimeout(hideTimeout);
    });
    
    // Hide dropdown when mouse leaves
    dropdown.addEventListener('mouseleave', () => {
        hideTimeout = setTimeout(() => {
            dropdown.classList.remove('show');
        }, 200);
    });
    
    trigger.addEventListener('mouseleave', (e) => {
        // Only hide if mouse is not going to dropdown
        if (!dropdown.matches(':hover')) {
            hideTimeout = setTimeout(() => {
                dropdown.classList.remove('show');
            }, 200);
        }
    });
    
    // Close on click outside
    document.addEventListener('click', (e) => {
        if (!userMenu.contains(e.target)) {
            dropdown.classList.remove('show');
        }
    });
    
    // Make dropdown links clickable
    dropdown.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', (e) => {
            e.stopPropagation();
            dropdown.classList.remove('show');
        });
    });
}

// ========== UI FUNCTIONS ==========
function showMessage(tabId, message, type) {
    const tab = document.getElementById(tabId);
    let messageDiv = tab.querySelector('.auth-message');
    
    if (!messageDiv) {
        messageDiv = document.createElement('div');
        messageDiv.className = 'auth-message';
        tab.querySelector('.auth-form').prepend(messageDiv);
    }
    
    messageDiv.textContent = message;
    messageDiv.className = `auth-message ${type}`;
    messageDiv.style.display = 'block';
    
    // Auto-hide after 5 seconds
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}

function updateUserUI(isLoggedIn, userData = null) {
    const loginLink = document.querySelector('a[onclick*="openAuthModal"]');
    if (!loginLink) return;
    
    if (isLoggedIn && userData) {
        const username = userData.username || sessionStorage.getItem('username');
        const userRole = userData.role || sessionStorage.getItem('userRole') || 'user';
        
        let menuHTML = `
            <div class="user-trigger">
                <span>👤 ${username}</span>
                <span class="dropdown-arrow">▼</span>
            </div>
            <ul class="user-dropdown">
                <li><a href="profile.php">My Profile</a></li>
                <li><a href="my_reviews.php">My Reviews</a></li>
                <li><a href="saved_places.php">Saved Places</a></li>
        `;
        
        // Add admin link if user is admin
        if (userRole === 'admin') {
            menuHTML += `<li><a href="admin_dashboard.php">Admin Panel</a></li>`;
        }
        
        menuHTML += `
                <li><hr></li>
                <li><a href="#" onclick="handleLogout(); return false;">Logout</a></li>
            </ul>
        `;
        
        loginLink.innerHTML = menuHTML;
        loginLink.onclick = null;
        loginLink.href = "#";
        loginLink.classList.add('user-menu');
        
        // Initialize dropdown
        setTimeout(() => {
            initUserDropdown();
        }, 50);
        
    } else {
        // Reset to login link
        loginLink.innerHTML = 'Login';
        loginLink.onclick = function(e) { 
            e.preventDefault(); 
            openAuthModal(); 
        };
        loginLink.href = "#";
        loginLink.classList.remove('user-menu');
    }
}

async function checkLoginStatus() {
    try {
        const response = await fetch('check_session.php');
        const data = await response.json();
        
        if (data.logged_in && data.user) {
            // Update sessionStorage
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('username', data.user.username);
            sessionStorage.setItem('userRole', data.user.role);
            sessionStorage.setItem('userId', data.user.id);
            
            updateUserUI(true, data.user);
        } else {
            sessionStorage.clear();
            updateUserUI(false);
        }
    } catch (error) {
        console.error('Session check error:', error);
        // If check fails, try to use sessionStorage
        const isLoggedIn = sessionStorage.getItem('isLoggedIn') === 'true';
        if (isLoggedIn) {
            const userData = {
                username: sessionStorage.getItem('username'),
                role: sessionStorage.getItem('userRole')
            };
            updateUserUI(true, userData);
        }
    }
}

// ========== INITIALIZATION ==========
document.addEventListener('DOMContentLoaded', function() {
    // Check login status
    checkLoginStatus();
    
    // Update Get Started button
    const getStartedBtn = document.querySelector('.hero .btn');
    if (getStartedBtn) {
        getStartedBtn.onclick = function(e) {
            e.preventDefault();
            openAuthModal();
            switchToSignup();
        };
    }
    
    // Add form event listeners
    const loginForm = document.getElementById('loginForm');
    const signupForm = document.getElementById('signupForm');
    
    if (loginForm) {
        loginForm.addEventListener('submit', handleLogin);
    }
    
    if (signupForm) {
        signupForm.addEventListener('submit', handleSignup);
    }
    
    // Password toggle listeners
    const showLoginPass = document.getElementById('showLoginPassword');
    if (showLoginPass) {
        showLoginPass.addEventListener('change', function() {
            togglePassword('loginPassword', this);
        });
    }
    
    // Initialize dropdown if already logged in
    if (document.querySelector('.user-menu')) {
        setTimeout(() => {
            initUserDropdown();
        }, 100);
    }
});