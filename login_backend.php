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
    $password = sanitize_input($_POST['password']);
    
    $sql = "SELECT * FROM users WHERE roll = ?";
    $params = ['s', $roll];
    $query_result = execute_query($sql, $params);
    $user = $query_result['result']->fetch_assoc();

    if ($user && password_verify($password, $user['password'])) {
        // Setting session variables
        $_SESSION['roll'] = $user['roll'];
        $_SESSION['sem_no'] = getSemesterNo($user['roll']);
        $_SESSION['webmail'] = $user['webmail'];
        $_SESSION['is_fac'] = $user['is_fac'];
        $_SESSION['is_head'] = $user['is_head'];
        $_SESSION['is_adean'] = $user['is_adean'];
        $_SESSION['is_dean'] = $user['is_dean'];
        $_SESSION['is_pic'] = $user['is_pic'];
        
        if ($user['is_fac'] == 1) {
            header('Location: faculty_pages/faculty_index.php');
        } else {
            header('Location: student_pages/nav.html');
        }
        exit();
    } else {
        die("Login failed. Invalid credentials.");
    }
}
?>
