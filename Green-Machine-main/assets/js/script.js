document.addEventListener('DOMContentLoaded', function() {
    // Login form handling
    const loginForm = document.getElementById("login-form");
    if (loginForm) {
        loginForm.addEventListener("submit", async function(e) {
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
    }

    // Signup form handling
    const signupForm = document.getElementById("signup-form");
    if (signupForm) {
        signupForm.addEventListener("submit", async function(e) {
            e.preventDefault();
            
            const errorElement = document.getElementById('error-message');
            errorElement.textContent = '';
            
            try {
                const formData = {
                    username: document.getElementById('username').value.trim(),
                    password: document.getElementById('password').value,
                    confirm_password: document.getElementById('confirm_password').value
                };

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
    }

    // Real-time validation
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const confirmPasswordInput = document.getElementById('confirm_password');

    if (usernameInput) usernameInput.addEventListener('input', validateUsername);
    if (passwordInput) passwordInput.addEventListener('input', validatePassword);
    if (confirmPasswordInput) confirmPasswordInput.addEventListener('input', validatePasswordMatch);

    function validateUsername() {
        const username = document.getElementById('username').value;
        const lengthReq = document.getElementById('username-length');
        const charsReq = document.getElementById('username-chars');

        if (username.length >= 4 && username.length <= 20) {
            lengthReq.classList.add('valid');
            lengthReq.classList.remove('invalid');
        } else {
            lengthReq.classList.add('invalid');
            lengthReq.classList.remove('valid');
        }

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
        
        const lengthReq = document.getElementById('password-length');
        const upperReq = document.getElementById('password-uppercase');
        const numberReq = document.getElementById('password-number');
        const specialReq = document.getElementById('password-special');

        lengthReq.classList.toggle('valid', password.length >= 8);
        upperReq.classList.toggle('valid', /[A-Z]/.test(password));
        numberReq.classList.toggle('valid', /[0-9]/.test(password));
        specialReq.classList.toggle('valid', /[^a-zA-Z0-9]/.test(password));

        let strength = 0;
        if (password.length >= 8) strength += 25;
        if (/[A-Z]/.test(password)) strength += 25;
        if (/[0-9]/.test(password)) strength += 25;
        if (/[^a-zA-Z0-9]/.test(password)) strength += 25;

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

    // Item popup functionality
    let selectedItem = {};

    document.querySelectorAll('.item').forEach(item => {
        item.addEventListener('click', function() {
            selectedItem.name = this.dataset.name;
            selectedItem.price = parseFloat(this.dataset.price);
            selectedItem.qty = 1;

            document.getElementById('popup-name').textContent = selectedItem.name;
            document.getElementById('popup-price').textContent = selectedItem.price.toFixed(2);
            document.getElementById('popup-img').src = `assets/images/${selectedItem.name.replace(/ /g, '_')}.jpg`;
            document.getElementById('popup-qty').textContent = selectedItem.qty;

            document.getElementById('popup-bg').style.display = 'block';
            document.getElementById('popup').style.display = 'block';
        });
    });

    // Quantity controls
    const qtyIncrease = document.getElementById('qty-increase');
    const qtyDecrease = document.getElementById('qty-decrease');

    if (qtyIncrease) {
        qtyIncrease.addEventListener('click', function() {
            selectedItem.qty++;
            document.getElementById('popup-qty').textContent = selectedItem.qty;
        });
    }

    if (qtyDecrease) {
        qtyDecrease.addEventListener('click', function() {
            if (selectedItem.qty > 1) selectedItem.qty--;
            document.getElementById('popup-qty').textContent = selectedItem.qty;
        });
    }

    // Popup background click to close
    const popupBg = document.getElementById('popup-bg');
    if (popupBg) {
        popupBg.addEventListener('click', function() {
            document.getElementById('popup').style.display = 'none';
            this.style.display = 'none';
        });
    }

    // Add to cart functionality
    const addToCartBtn = document.getElementById('add-to-cart');
    if (addToCartBtn) {
        addToCartBtn.addEventListener('click', async function() {
            try {
                const response = await fetch('add_to_cart.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `name=${encodeURIComponent(selectedItem.name)}&price=${selectedItem.price}&qty=${selectedItem.qty}`
                });
                
                if (response.ok) {
                    alert('Added to cart!');
                    document.getElementById('popup').style.display = 'none';
                    document.getElementById('popup-bg').style.display = 'none';
                } else {
                    throw new Error('Failed to add to cart');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to add item to cart. Please try again.');
            }
        });
    }
});