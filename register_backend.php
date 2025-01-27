<?php
session_start();
require_once 'functions.php';
/**
 * Get the semester number from the year
 * Implement your logic here to derive the semester number.
 */
function getSemesterNo($rollno) {
    $currentYear = date('Y');
    $currentMonth = date('m');
    $yearOfAdmission = '20' . substr($rollno, 0, 2);
    $yearsSinceAdmission = $currentYear - $yearOfAdmission;
    $semesterNo = ($yearsSinceAdmission * 2) + 1;

    if ($currentMonth < 6) {
        $semesterNo += 1;
    }

    return $semesterNo;
}

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
        $_SESSION['sem_no'] = 7; //getSemesterNo($roll);
        $_SESSION['webmail'] = $webmail;
        $_SESSION['is_fac'] = 0;
        $_SESSION['is_head'] = 0;
        $_SESSION['is_adean'] = 0;
        $_SESSION['is_dean'] = 0;
        $_SESSION['is_pic'] = 0;
        header('Location: student_pages/nav.html');
        exit();
    } else {
        die("Registration failed. Try again.");
    }
}
?>
