/**
 * Smart Knowledge Discovery Portal - JavaScript
 * Handles form validation, interactivity, and user authentication (demo)
 */

// Wait for DOM to be fully loaded
document.addEventListener('DOMContentLoaded', function() {
    
    // Initialize all functionality
    initPasswordToggles();
    initFormValidation();
    initPasswordStrength();
    
    /**
     * Password Toggle Functionality
     * Shows/hides password in password fields
     */
    function initPasswordToggles() {
        // Toggle for login password
        const togglePassword = document.getElementById('togglePassword');
        if (togglePassword) {
            togglePassword.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const eyeIcon = document.getElementById('eyeIcon');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }
        
        // Toggle for register password
        const toggleRegisterPassword = document.getElementById('togglePassword');
        if (toggleRegisterPassword) {
            toggleRegisterPassword.addEventListener('click', function() {
                const passwordInput = document.getElementById('password');
                const confirmPasswordInput = document.getElementById('confirmPassword');
                const eyeIcon = document.getElementById('eyeIcon');
                const eyeIconConfirm = document.getElementById('eyeIconConfirm');
                
                if (passwordInput.type === 'password') {
                    passwordInput.type = 'text';
                    eyeIcon.classList.remove('fa-eye');
                    eyeIcon.classList.add('fa-eye-slash');
                } else {
                    passwordInput.type = 'password';
                    eyeIcon.classList.remove('fa-eye-slash');
                    eyeIcon.classList.add('fa-eye');
                }
            });
        }
        
        // Toggle for confirm password
        const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
        if (toggleConfirmPassword) {
            toggleConfirmPassword.addEventListener('click', function() {
                const confirmPasswordInput = document.getElementById('confirmPassword');
                const eyeIconConfirm = document.getElementById('eyeIconConfirm');
                
                if (confirmPasswordInput.type === 'password') {
                    confirmPasswordInput.type = 'text';
                    eyeIconConfirm.classList.remove('fa-eye');
                    eyeIconConfirm.classList.add('fa-eye-slash');
                } else {
                    confirmPasswordInput.type = 'password';
                    eyeIconConfirm.classList.remove('fa-eye-slash');
                    eyeIconConfirm.classList.add('fa-eye');
                }
            });
        }
    }
    
    /**
     * Password Strength Indicator
     * Shows password strength as user types
     */
    function initPasswordStrength() {
        const passwordInput = document.getElementById('password');
        if (!passwordInput) return;
        
        passwordInput.addEventListener('input', function() {
            const password = this.value;
            const strengthBar1 = document.getElementById('strengthBar1');
            const strengthBar2 = document.getElementById('strengthBar2');
            const strengthBar3 = document.getElementById('strengthBar3');
            const strengthBar4 = document.getElementById('strengthBar4');
            const strengthText = document.getElementById('passwordStrength');
            
            if (!password) {
                resetStrengthBars();
                strengthText.textContent = '';
                return;
            }
            
            const strength = calculatePasswordStrength(password);
            
            // Reset all bars
            resetStrengthBars();
            
            // Update bars based on strength
            const colors = {
                weak: '#ef4444',
                fair: '#f59e0b',
                good: '#10b981',
                strong: '#3b82f6'
            };
            
            if (strength >= 1) {
                strengthBar1.style.background = colors.weak;
            }
            if (strength >= 2) {
                strengthBar2.style.background = colors.fair;
            }
            if (strength >= 3) {
                strengthBar3.style.background = colors.good;
            }
            if (strength >= 4) {
                strengthBar4.style.background = colors.strong;
            }
            
            // Update text
            const strengthLabels = ['', 'Weak', 'Fair', 'Good', 'Strong'];
            strengthText.textContent = strengthLabels[strength] ? `${strengthLabels[strength]} password` : '';
            strengthText.style.color = colors[Object.keys(colors)[strength - 1]] || colors.weak;
        });
    }
    
    /**
     * Calculate Password Strength
     * Returns a number from 0-4 based on password complexity
     */
    function calculatePasswordStrength(password) {
        let score = 0;
        
        // Length check
        if (password.length >= 8) score++;
        if (password.length >= 12) score++;
        
        // Character variety checks
        if (/[a-z]/.test(password)) score++;
        if (/[A-Z]/.test(password)) score++;
        if (/[0-9]/.test(password)) score++;
        if (/[^a-zA-Z0-9]/.test(password)) score++;
        
        // Return normalized score (0-4)
        if (score <= 1) return 1;
        if (score <= 2) return 2;
        if (score <= 3) return 3;
        return 4;
    }
    
    /**
     * Reset Password Strength Bars
     */
    function resetStrengthBars() {
        const bars = ['strengthBar1', 'strengthBar2', 'strengthBar3', 'strengthBar4'];
        bars.forEach(id => {
            const bar = document.getElementById(id);
            if (bar) {
                bar.style.background = 'var(--border)';
            }
        });
    }
    
    /**
     * Form Validation
     * Handles validation for login and register forms
     */
    function initFormValidation() {
        // Login Form
        const loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateLoginForm()) {
                    handleLogin();
                }
            });
        }
        
        // Register Form
        const registerForm = document.getElementById('registerForm');
        if (registerForm) {
            registerForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (validateRegisterForm()) {
                    handleRegistration();
                }
            });
        }
        
        // Real-time validation on blur
        const formControls = document.querySelectorAll('.form-control');
        formControls.forEach(control => {
            control.addEventListener('blur', function() {
                validateField(this);
            });
            
            control.addEventListener('input', function() {
                if (this.classList.contains('is-invalid')) {
                    validateField(this);
                }
            });
        });
    }
    
    /**
     * Validate Login Form
     */
    function validateLoginForm() {
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        let isValid = true;
        
        // Validate username
        if (!username.value.trim()) {
            setInvalid(username, 'Please enter your username or email');
            isValid = false;
        } else {
            setValid(username);
        }
        
        // Validate password
        if (!password.value) {
            setInvalid(password, 'Please enter your password');
            isValid = false;
        } else {
            setValid(password);
        }
        
        return isValid;
    }
    
    /**
     * Validate Register Form
     */
    function validateRegisterForm() {
        const fullName = document.getElementById('fullName');
        const email = document.getElementById('email');
        const username = document.getElementById('username');
        const password = document.getElementById('password');
        const confirmPassword = document.getElementById('confirmPassword');
        const terms = document.getElementById('terms');
        
        let isValid = true;
        
        // Validate full name
        if (!fullName.value.trim()) {
            setInvalid(fullName, 'Please enter your full name');
            isValid = false;
        } else if (fullName.value.trim().length < 2) {
            setInvalid(fullName, 'Name must be at least 2 characters');
            isValid = false;
        } else {
            setValid(fullName);
        }
        
        // Validate email
        if (!email.value.trim()) {
            setInvalid(email, 'Please enter your email address');
            isValid = false;
        } else if (!isValidEmail(email.value)) {
            setInvalid(email, 'Please enter a valid email address');
            isValid = false;
        } else {
            setValid(email);
        }
        
        // Validate username
        if (!username.value.trim()) {
            setInvalid(username, 'Please choose a username');
            isValid = false;
        } else if (username.value.trim().length < 3) {
            setInvalid(username, 'Username must be at least 3 characters');
            isValid = false;
        } else if (!/^[a-zA-Z0-9_]+$/.test(username.value)) {
            setInvalid(username, 'Username can only contain letters, numbers, and underscores');
            isValid = false;
        } else {
            setValid(username);
        }
        
        // Validate password
        if (!password.value) {
            setInvalid(password, 'Please create a password');
            isValid = false;
        } else if (password.value.length < 8) {
            setInvalid(password, 'Password must be at least 8 characters');
            isValid = false;
        } else {
            setValid(password);
        }
        
        // Validate confirm password
        if (!confirmPassword.value) {
            setInvalid(confirmPassword, 'Please confirm your password');
            isValid = false;
        } else if (confirmPassword.value !== password.value) {
            setInvalid(confirmPassword, 'Passwords do not match');
            isValid = false;
        } else {
            setValid(confirmPassword);
        }
        
        // Validate terms
        if (!terms.checked) {
            terms.parentElement.classList.add('is-invalid');
            isValid = false;
        } else {
            terms.parentElement.classList.remove('is-invalid');
        }
        
        return isValid;
    }
    
    /**
     * Validate Single Field
     */
    function validateField(field) {
        const fieldId = field.id;
        
        switch(fieldId) {
            case 'username':
            case 'email':
                if (!field.value.trim()) {
                    setInvalid(field, 'This field is required');
                } else if (fieldId === 'email' && !isValidEmail(field.value)) {
                    setInvalid(field, 'Please enter a valid email');
                } else {
                    setValid(field);
                }
                break;
            case 'password':
                if (!field.value) {
                    setInvalid(field, 'Password is required');
                } else if (field.value.length < 8) {
                    setInvalid(field, 'Password must be at least 8 characters');
                } else {
                    setValid(field);
                }
                break;
            case 'confirmPassword':
                const password = document.getElementById('password');
                if (!field.value) {
                    setInvalid(field, 'Please confirm your password');
                } else if (field.value !== password.value) {
                    setInvalid(field, 'Passwords do not match');
                } else {
                    setValid(field);
                }
                break;
            case 'fullName':
                if (!field.value.trim()) {
                    setInvalid(field, 'Please enter your name');
                } else {
                    setValid(field);
                }
                break;
        }
    }
    
    /**
     * Set Field as Invalid
     */
    function setInvalid(field, message) {
        field.classList.remove('is-valid');
        field.classList.add('is-invalid');
        
        let feedback = field.parentElement.querySelector('.invalid-feedback');
        if (!feedback) {
            feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            field.parentElement.appendChild(feedback);
        }
        feedback.textContent = message;
    }
    
    /**
     * Set Field as Valid
     */
    function setValid(field) {
        field.classList.remove('is-invalid');
        field.classList.add('is-valid');
    }
    
    /**
     * Validate Email Format
     */
    function isValidEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return emailRegex.test(email);
    }
    
    /**
     * Handle Login
     * Demo login functionality with localStorage
     */
    function handleLogin() {
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const alertMessage = document.getElementById('alertMessage');
        
        // Get stored users from localStorage
        const users = JSON.parse(localStorage.getItem('skdp_users') || '[]');
        
        // Find matching user
        const user = users.find(u => 
            (u.username === username || u.email === username) && u.password === password
        );
        
        if (user) {
            // Login successful
            showAlert('Login successful! Redirecting...', 'success');
            
            // Store current user session
            localStorage.setItem('skdp_current_user', JSON.stringify(user));
            
            // Redirect after delay
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1500);
        } else {
            // Demo mode: accept any login for demonstration
            // In production, this would validate against a real backend
            showAlert('Demo mode: Login successful! Redirecting...', 'success');
            
            const demoUser = {
                username: username,
                email: username.includes('@') ? username : username + '@demo.com',
                fullName: 'Demo User'
            };
            
            localStorage.setItem('skdp_current_user', JSON.stringify(demoUser));
            
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1500);
        }
    }
    
    /**
     * Handle Registration
     * Demo registration functionality with localStorage
     */
    function handleRegistration() {
        const fullName = document.getElementById('fullName').value;
        const email = document.getElementById('email').value;
        const username = document.getElementById('username').value;
        const password = document.getElementById('password').value;
        const alertMessage = document.getElementById('alertMessage');
        
        // Get existing users
        const users = JSON.parse(localStorage.getItem('skdp_users') || '[]');
        
        // Check if user already exists
        if (users.some(u => u.email === email)) {
            showAlert('An account with this email already exists', 'error');
            return;
        }
        
        if (users.some(u => u.username === username)) {
            showAlert('This username is already taken', 'error');
            return;
        }
        
        // Create new user
        const newUser = {
            id: Date.now(),
            fullName: fullName,
            email: email,
            username: username,
            password: password,
            createdAt: new Date().toISOString()
        };
        
        // Save to localStorage
        users.push(newUser);
        localStorage.setItem('skdp_users', JSON.stringify(users));
        
        // Show success message
        showAlert('Account created successfully! Redirecting to login...', 'success');
        
        // Redirect to login after delay
        setTimeout(() => {
            window.location.href = 'login.html';
        }, 2000);
    }
    
    /**
     * Show Alert Message
     */
    function showAlert(message, type) {
        const alertMessage = document.getElementById('alertMessage');
        if (!alertMessage) return;
        
        alertMessage.textContent = message;
        alertMessage.className = 'alert show alert-' + type;
        
        // Auto-hide after 5 seconds
        setTimeout(() => {
            alertMessage.classList.remove('show');
        }, 5000);
    }
    
    /**
     * Check if user is logged in
     * Useful for protected pages
     */
    function isLoggedIn() {
        return localStorage.getItem('skdp_current_user') !== null;
    }
    
    /**
     * Logout function
     */
    window.logout = function() {
        localStorage.removeItem('skdp_current_user');
        window.location.href = 'login.html';
    };
    
    /**
     * Get current user
     */
    window.getCurrentUser = function() {
        return JSON.parse(localStorage.getItem('skdp_current_user') || 'null');
    };
});

/**
 * Additional utility functions
 */

// Smooth scroll for anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function(e) {
        const href = this.getAttribute('href');
        if (href !== '#') {
            e.preventDefault();
            const target = document.querySelector(href);
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        }
    });
});

// Add navbar background on scroll
window.addEventListener('scroll', function() {
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        if (window.scrollY > 50) {
            navbar.style.boxShadow = 'var(--shadow-md)';
        } else {
            navbar.style.boxShadow = 'none';
        }
    }
});

// Console welcome message
console.log('%c🚀 Smart Knowledge Discovery Portal', 'font-size: 20px; font-weight: bold; color: #3b82f6;');
console.log('%cWelcome to your knowledge management solution!', 'color: #64748b;');
