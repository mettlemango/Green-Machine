document.getElementById("login-form").addEventListener("submit", async function(e) {
    e.preventDefault();
    
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    try {
        const response = await fetch('login.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `username=${encodeURIComponent(username)}&password=${encodeURIComponent(password)}`
        });
        
        const data = await response.json();
        
        if (data.success) {
            window.location.href = data.redirect;
        } else {
            document.getElementById("error-message").textContent = data.message;
        }
    } catch (error) {
        console.error('Error:', error);
        document.getElementById("error-message").textContent = "Login failed";
    }
});

// Real-time validation
document.getElementById('username').addEventListener('input', validateUsername);
document.getElementById('password').addEventListener('input', validatePassword);
document.getElementById('confirm_password').addEventListener('input', validatePasswordMatch);

function validateUsername() {
    const username = document.getElementById('username').value;
    const lengthReq = document.getElementById('username-length');
    const charsReq = document.getElementById('username-chars');

    // Length check
    if (username.length >= 4 && username.length <= 20) {
        lengthReq.classList.add('valid');
        lengthReq.classList.remove('invalid');
    } else {
        lengthReq.classList.add('invalid');
        lengthReq.classList.remove('valid');
    }

    // Character check
    if (/^[a-zA-Z0-9_]+$/.test(username)) {
        charsReq.classList.add('valid');
        charsReq.classList.remove('invalid');
    } else {
        charsReq.classList.add('invalid');
        charsReq.classList.remove('valid');
    }
}

function validatePassword() {
    const password = document.getElementById('password').value;
    const strengthBar = document.getElementById('password-strength-bar');
    
    // Requirements
    const lengthReq = document.getElementById('password-length');
    const upperReq = document.getElementById('password-uppercase');
    const numberReq = document.getElementById('password-number');
    const specialReq = document.getElementById('password-special');

    // Validate each requirement
    lengthReq.classList.toggle('valid', password.length >= 8);
    upperReq.classList.toggle('valid', /[A-Z]/.test(password));
    numberReq.classList.toggle('valid', /[0-9]/.test(password));
    specialReq.classList.toggle('valid', /[^a-zA-Z0-9]/.test(password));

    // Calculate strength
    let strength = 0;
    if (password.length >= 8) strength += 25;
    if (/[A-Z]/.test(password)) strength += 25;
    if (/[0-9]/.test(password)) strength += 25;
    if (/[^a-zA-Z0-9]/.test(password)) strength += 25;

    // Update strength bar
    strengthBar.style.width = strength + '%';
    strengthBar.style.backgroundColor = 
        strength < 50 ? 'red' : 
        strength < 75 ? 'orange' : 'green';
}

function validatePasswordMatch() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const matchReq = document.getElementById('password-match');

    matchReq.classList.toggle('valid', password === confirmPassword && password !== '');
    matchReq.classList.toggle('invalid', password !== confirmPassword && password !== '' && confirmPassword !== '');
}

document.getElementById('signup-form').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const errorElement = document.getElementById('error-message');
    errorElement.textContent = '';
    
    try {
        // Get form values
        const formData = {
            username: document.getElementById('username').value.trim(),
            password: document.getElementById('password').value,
            confirm_password: document.getElementById('confirm_password').value
        };

        // Send as JSON
        const response = await fetch('signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(formData)
        });

        if (!response.ok) throw new Error('Network error');
        
        const data = await response.json();
        
        if (data.success) {
            errorElement.style.color = 'green';
            errorElement.textContent = data.message;
            setTimeout(() => window.location.href = 'login.html', 1500);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        errorElement.style.color = 'red';
        errorElement.textContent = error.message || 'Registration failed. Please try again.';
        console.error('Error:', error);
    }
});