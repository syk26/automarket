const patterns = {
    name: /^[A-Za-z\s]+$/,
    address: /^[A-Za-z0-9\s]+$/,
    phone: /^1[3-9]\d{9}$/,
    email: /^[^\s@]+@[^\s@]+\.(cn|com)$/,
    username: /^[A-Za-z0-9]{6,}$/,
    password: /^[A-Za-z0-9]{6,}$/,
    price: /^\d+$/,
    year: /^\d{4}$/
};

function toggleError(input, isValid, message) {
    const formGroup = input.closest('.form-group');
    const errorElement = formGroup ? formGroup.querySelector('.error-message') : null;
    
    if (!isValid) {
        input.classList.add('invalid');
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
        return false;
    } else {
        input.classList.remove('invalid');
        if (errorElement) {
            errorElement.classList.remove('show');
        }
        return true;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    const regForm = document.getElementById('registration-form');
    if (regForm) {
        regForm.addEventListener('submit', function(e) {
            let isValid = true;

            const name = document.getElementById('name');
            const address = document.getElementById('address');
            const phone = document.getElementById('phone');
            const email = document.getElementById('email');
            const username = document.getElementById('username');
            const password = document.getElementById('password');

            if (name && !toggleError(name, patterns.name.test(name.value), 'Name must contain only alphabetical letters and spaces.')) isValid = false;
            if (address && !toggleError(address, patterns.address.test(address.value), 'Address must contain only alphanumeric characters and spaces.')) isValid = false;
            if (phone && !toggleError(phone, patterns.phone.test(phone.value), 'Phone number must be a valid China mobile number (11 digits starting with 1).')) isValid = false;
            if (email && !toggleError(email, patterns.email.test(email.value), 'Email must contain @ exactly once and end with .cn or .com')) isValid = false;
            if (username && !toggleError(username, patterns.username.test(username.value), 'Username must be at least 6 alphanumeric characters.')) isValid = false;
            if (password && !toggleError(password, patterns.password.test(password.value), 'Password must be at least 6 alphanumeric characters.')) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    const loginForm = document.getElementById('login-form');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            let isValid = true;

            const username = document.getElementById('login-username');
            const password = document.getElementById('login-password');

            if (username && !toggleError(username, patterns.username.test(username.value), 'Invalid username format.')) isValid = false;
            if (password && !toggleError(password, patterns.password.test(password.value), 'Invalid password format.')) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }

    const carForm = document.getElementById('add-car-form');
    if (carForm) {
        carForm.addEventListener('submit', function(e) {
            let isValid = true;

            const color = document.getElementById('colour');
            const model = document.getElementById('model');
            const year = document.getElementById('year');
            const location = document.getElementById('location');
            const price = document.getElementById('price');
            const image = document.getElementById('image');

            if (color && !toggleError(color, color.value.trim() !== '', 'Please enter a colour.')) isValid = false;
            if (model && !toggleError(model, model.value.trim() !== '', 'Please enter a model.')) isValid = false;
            if (year && !toggleError(year, patterns.year.test(year.value), 'Please enter a valid 4-digit year.')) isValid = false;
            if (location && !toggleError(location, location.value.trim() !== '', 'Please enter a location.')) isValid = false;
            if (price && !toggleError(price, patterns.price.test(price.value), 'Please enter a valid price (numbers only).')) isValid = false;
            if (image && image.value && !toggleError(image, image.value.trim() !== '', 'Please upload an image.')) isValid = false;

            if (!isValid) {
                e.preventDefault();
            }
        });
    }
});
