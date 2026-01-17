 // auth.js — Authentication & UI Logic

// ================= MODAL FUNCTIONS =================
function openAuthModal() {
    document.getElementById('authModal').style.display = 'block';
    document.body.style.overflow = 'hidden';
    openTab(null, 'loginTab');
}

function closeAuthModal() {
    document.getElementById('authModal').style.display = 'none';
    document.body.style.overflow = 'auto';
}

// Close modal when clicking outside
window.addEventListener('click', (e) => {
    const modal = document.getElementById('authModal');
    if (e.target === modal) closeAuthModal();
});

// Close modal with Escape key
document.addEventListener('keydown', (e) => {
    if (e.key === 'Escape') closeAuthModal();
});

// ================= TAB FUNCTIONS =================
function openTab(evt, tabName) {
    document.querySelectorAll('.tab-content').forEach(tab =>
        tab.classList.remove('active')
    );
    document.querySelectorAll('.tab-btn').forEach(btn =>
        btn.classList.remove('active')
    );

    document.getElementById(tabName).classList.add('active');

    if (evt) evt.currentTarget.classList.add('active');
}

function switchToSignup() {
    openTab(null, 'signupTab');
}

function switchToLogin() {
    openTab(null, 'loginTab');
}

// ================= PASSWORD TOGGLE =================
function togglePassword(inputId, checkbox) {
    const input = document.getElementById(inputId);
    if (input) input.type = checkbox.checked ? 'text' : 'password';
}

// ================= PASSWORD STRENGTH =================
document.addEventListener('DOMContentLoaded', () => {
    const passwordInput = document.getElementById('signupPassword');
    if (!passwordInput) return;

    passwordInput.addEventListener('input', () => {
        const bar = document.querySelector('.strength-bar');
        const text = document.getElementById('passwordStrength');
        if (!bar || !text) return;

        let strength = 0;
        if (passwordInput.value.length >= 6) strength += 25;
        if (/[A-Z]/.test(passwordInput.value) && /[a-z]/.test(passwordInput.value)) strength += 25;
        if (/\d/.test(passwordInput.value)) strength += 25;
        if (/[^a-zA-Z\d]/.test(passwordInput.value)) strength += 25;

        bar.style.width = strength + '%';

        if (strength >= 75) {
            bar.style.background = '#27ae60';
            text.textContent = 'Strong';
        } else if (strength >= 50) {
            bar.style.background = '#f39c12';
            text.textContent = 'Medium';
        } else {
            bar.style.background = '#e74c3c';
            text.textContent = 'Weak';
        }
    });
});

// ================= FORM VALIDATION =================
function validateSignupForm() {
    const username = document.getElementById('signupUsername').value.trim();
    const email = document.getElementById('signupEmail').value.trim();
    const password = document.getElementById('signupPassword').value;
    const confirm = document.getElementById('signupConfirmPassword').value;


    if (username.length < 3) errors.push('Username too short');
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) errors.push('Invalid email');
    if (password.length < 6) errors.push('Password too short');
    if (password !== confirm) errors.push('Passwords do not match');

    return errors;
}

// ================= AUTH FUNCTIONS =================
async function handleLogin(e) {
    e.preventDefault();
    const btn = e.target.querySelector('.auth-btn');

    btn.disabled = true;
    btn.textContent = 'Signing in...';

    try {
        const res = await fetch('login.php', {
            method: 'POST',
            body: new FormData(e.target)
        });

        const data = await res.json();

        if (data.success) {
            sessionStorage.setItem('isLoggedIn', 'true');
            sessionStorage.setItem('username', data.user.username);
            sessionStorage.setItem('userRole', data.user.role);
            sessionStorage.setItem('userId', data.user.id);

            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                closeAuthModal();
                updateUserUI(true, data.user);
            }
        } else {
            showMessage('loginTab', data.message, 'error');
        }
    } catch {
        showMessage('loginTab', 'Login failed', 'error');
    }

    btn.disabled = false;
    btn.textContent = 'Sign In';
}

async function handleSignup(e) {
    e.preventDefault();
    const errors = validateSignupForm();
    if (errors.length) {
        showMessage('signupTab', errors.join(', '), 'error');
        return;
    }

    const btn = e.target.querySelector('.auth-btn');
    btn.disabled = true;
    btn.textContent = 'Creating account...';

    try {
        const res = await fetch('register.php', {
            method: 'POST',
            body: new FormData(e.target)
        });

        const data = await res.json();

        if (data.success) {
            closeAuthModal();
            updateUserUI(true, data.user);
        } else {
            showMessage('signupTab', data.message, 'error');
        }
    } catch {
        showMessage('signupTab', 'Signup failed', 'error');
    }

    btn.disabled = false;
    btn.textContent = 'Sign Up';
}

async function handleLogout() {
    await fetch('logout.php');
    sessionStorage.clear();
    window.location.href = 'index.php';
}

// ================= UI HELPERS =================
function showMessage(tabId, msg, type) {
    const tab = document.getElementById(tabId);
    let box = tab.querySelector('.auth-message');

    if (!box) {
        box = document.createElement('div');
        box.className = 'auth-message';
        tab.prepend(box);
    }

    box.textContent = msg;
    box.className = `auth-message ${type}`;
    setTimeout(() => box.remove(), 5000);
}

function updateUserUI(isLoggedIn, user) {
    const link = document.querySelector('.auth-link');
    if (!link) return;

    if (isLoggedIn) {
        link.innerHTML = `
            <span>👤 ${user.username}</span>
            <ul class="user-dropdown">
                <li><a href="profile.php">Profile</a></li>
                <li><a href="#" onclick="handleLogout()">Logout</a></li>
            </ul>`;
        link.classList.add('user-menu');
    } else {
        link.innerHTML = 'Login';
        link.onclick = openAuthModal;
    }
}

// ================= SESSION CHECK =================
async function checkLoginStatus() {
    try {
        const res = await fetch('check_session.php');
        const data = await res.json();
        if (data.logged_in) updateUserUI(true, data.user);
    } catch {}
}

// ================= INITIALIZATION =================
document.addEventListener('DOMContentLoaded', () => {
    checkLoginStatus();

    const params = new URLSearchParams(window.location.search);
    if (params.get('login') === 'required') {
        openAuthModal();
        switchToLogin();
    }

    loginForm?.addEventListener('submit', handleLogin);
    signupForm?.addEventListener('submit', handleSignup);

    showLoginPassword?.addEventListener('change', function () {
        togglePassword('loginPassword', this);
    });
});
