<?php
session_start();
require_once 'functions.php';

// CSRF Token for form security
$csrf_token = generate_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login / Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            max-width: 450px;
            margin-top: 50px;
            background: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .tabs {
            border-bottom: 2px solid #dee2e6;
            margin-bottom: 20px;
        }
        .tab-pane {
            padding-top: 20px;
        }
        .password-requirements {
            margin-top: 10px;
            font-size: 0.9em;
        }
        .valid {
            color: green;
        }
        .invalid {
            color: red;
        }
    </style>
    <script>
        // Ensure roll number is capitalized for login
        function capitalizeLoginRoll() {
            let rollInput = document.getElementById('login_roll');
            rollInput.value = rollInput.value.toUpperCase();
        }

        // Ensure roll number is capitalized for registration
        function capitalizeRegisterRoll() {
            let rollInput = document.getElementById('register_roll');
            rollInput.value = rollInput.value.toUpperCase();
        }

        // Ensure email has '@iitp.ac.in' domain before submission (for registration)
        function validateRegisterEmail() {
            const emailInput = document.getElementById('register_webmail');
            const domain = '@iitp.ac.in';

            if (!emailInput.value.endsWith(domain)) {
                alert('Email must end with @iitp.ac.in');
                return false;
            }
            return true;
        }

        // Password validation (for registration)
        function validateRegisterPassword() {
            const password = document.getElementById('register_password').value;
            const lengthRequirement = document.getElementById('length');
            const numberRequirement = document.getElementById('number');
            const specialCharRequirement = document.getElementById('special-char');

            // Length requirement
            if (password.length >= 8) {
                lengthRequirement.classList.remove('invalid');
                lengthRequirement.classList.add('valid');
            } else {
                lengthRequirement.classList.remove('valid');
                lengthRequirement.classList.add('invalid');
            }

            // Number requirement
            if (/\d/.test(password)) {
                numberRequirement.classList.remove('invalid');
                numberRequirement.classList.add('valid');
            } else {
                numberRequirement.classList.remove('valid');
                numberRequirement.classList.add('invalid');
            }

            // Special character requirement
            if (/[!@#$%^&*(),.?":{}|<>]/.test(password)) {
                specialCharRequirement.classList.remove('invalid');
                specialCharRequirement.classList.add('valid');
            } else {
                specialCharRequirement.classList.remove('valid');
                specialCharRequirement.classList.add('invalid');
            }
        }

        // Validate the registration form
        function validateRegisterForm() {
            capitalizeRegisterRoll();
            if (!validateRegisterEmail()) {
                return false;
            }
            validateRegisterPassword();
            return true;
        }
    </script>
</head>
<body>
    <div class="container">
        <ul class="nav nav-tabs tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="login-tab" data-bs-toggle="tab" data-bs-target="#login" type="button" role="tab">Login</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="register-tab" data-bs-toggle="tab" data-bs-target="#register" type="button" role="tab">Register</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="login" role="tabpanel">
                <form action="login_backend.php" method="POST" onsubmit="capitalizeLoginRoll()">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div class="mb-3">
                        <label for="login_roll" class="form-label">Roll Number</label>
                        <input type="text" class="form-control" id="login_roll" name="roll" required>
                    </div>
                    <div class="mb-3">
                        <label for="login_webmail" class="form-label">Webmail</label>
                        <input type="email" class="form-control" id="login_webmail" name="webmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="login_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="login_password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Login</button>
                </form>
            </div>
            <div class="tab-pane fade" id="register" role="tabpanel">
                <form action="register_backend.php" method="POST" onsubmit="return validateRegisterForm()">
                    <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                    <div class="mb-3">
                        <label for="register_roll" class="form-label">Roll Number</label>
                        <input type="text" class="form-control" id="register_roll" name="roll" required>
                    </div>
                    <div class="mb-3">
                        <label for="register_webmail" class="form-label">Webmail</label>
                        <input type="email" class="form-control" id="register_webmail" name="webmail" required>
                    </div>
                    <div class="mb-3">
                        <label for="register_password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="register_password" name="password" required onkeyup="validateRegisterPassword()">
                        <div class="password-requirements">
                            <p id="length" class="invalid">Minimum 8 characters</p>
                            <p id="number" class="invalid">At least one number</p>
                            <p id="special-char" class="invalid">At least one special character</p>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
