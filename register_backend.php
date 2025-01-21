<?php
session_start();
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $csrf_token = sanitize_input($_POST['csrf_token']);
    check_csrf_token($csrf_token);

    $roll = sanitize_input($_POST['roll']);
    $webmail = sanitize_input($_POST['webmail']);
    $password = sanitize_input($_POST['password']);
    
    // Password validation
    if (strlen($password) < 8 || 
        !preg_match('/[0-9]/', $password) || 
        !preg_match('/[A-Za-z]/', $password) || 
        !preg_match('/[\W]/', $password)) {
        die("Password does not meet security requirements.");
    }

    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO users (roll, webmail, password) VALUES (?, ?, ?)";
    $params = ['sss', $roll, $webmail, $hashed_password];
    $query_result = execute_query($sql, $params);

    if ($query_result['success']) {
        $_SESSION['roll'] = $roll;
        $_SESSION['webmail'] = $webmail;
        $_SESSION['is_fac'] = 0;
        $_SESSION['is_head'] = 0;
        $_SESSION['is_adean'] = 0;
        $_SESSION['is_dean'] = 0;
        $_SESSION['is_pic'] = 0;
        header('Location: student_pages/student_index.php');
        exit();
    } else {
        die("Registration failed. Try again.");
    }
}
?>
