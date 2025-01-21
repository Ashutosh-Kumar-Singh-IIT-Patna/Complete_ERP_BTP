<?php
session_start();
require_once '../functions.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    check_csrf_token($_POST['csrf_token']);
    
    $roll_number = strtoupper(sanitize_input($_POST['roll_number']));
    $full_name = ucwords(sanitize_input($_POST['full_name']));
    $preferred_name = ucwords(sanitize_input($_POST['preferred_name']));
    $dob = sanitize_input($_POST['dob']);
    $gender = sanitize_input($_POST['gender']);
    $mother_tongue = sanitize_input($_POST['mother_tongue']);
    $religion = sanitize_input($_POST['religion']);
    $nationality = sanitize_input($_POST['nationality']);
    $state = sanitize_input($_POST['state']);
    $blood_group = sanitize_input($_POST['blood_group']);
    $category = strtoupper(sanitize_input($_POST['category']));
    $country_code = sanitize_input($_POST['country_code']);
    $mobile_no = ltrim(sanitize_input($_POST['mobile_no']), '0');
    $whatsapp_no = ltrim(sanitize_input($_POST['whatsapp_no']), '0');
    $course = strtoupper(sanitize_input($_POST['course']));
    $department = sanitize_input($_POST['department']);
    $branch = sanitize_input($_POST['branch']);
    $specialization = sanitize_input($_POST['specialization']);
    $hostel_name = sanitize_input($_POST['hostel_name']);
    $hostel_block = sanitize_input($_POST['hostel_block']);
    $hostel_room_no = sanitize_input($_POST['hostel_room_no']);
    $webmail = strtolower(sanitize_input($_POST['webmail']));
    $alt_mail = strtolower(sanitize_input($_POST['alt_mail']));
    $date_of_admission = sanitize_input($_POST['date_of_admission']);
    $father_name = ucwords(sanitize_input($_POST['father_name']));
    $mother_name = ucwords(sanitize_input($_POST['mother_name']));
    $father_phone = sanitize_input($_POST['father_phone']);
    $mother_phone = sanitize_input($_POST['mother_phone']);
    $father_profession = sanitize_input($_POST['father_profession']);
    $mother_profession = sanitize_input($_POST['mother_profession']);
    $annual_income = sanitize_input($_POST['annual_income']);

    // Check if roll_number already exists
    $check_sql = "SELECT COUNT(*) as count FROM acad_users WHERE roll_number = ?";
    $check_params = ['s', $roll_number];
    $check_result = execute_query($check_sql, $check_params);

    if ($check_result['success']) {
        $row = $check_result['result']->fetch_assoc();
        if ($row['count'] > 0) {
            echo "Roll number already exists. Please use a different roll number.";
            return;
        }
    }

    // Handle photo upload
    $photo_filename = $roll_number . '.jpg';
    $photo_path = 'uploads/profile_images/' . $photo_filename;
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        if (!move_uploaded_file($_FILES['photo']['tmp_name'], $photo_path)) {
            echo "Failed to upload photo. Please try again.";
            return;
        }
    } else {
        echo "Photo upload is required. Please try again.";
        return;
    }

    // Proceed with insertion
    $sql = "INSERT INTO acad_users (
                roll_number, full_name, preferred_name, dob, gender, 
                mother_tongue, religion, nationality, state, blood_group, 
                category, country_code, mobile_no, whatsapp_no, course, 
                department, branch, specialization, hostel_name, hostel_block, 
                hostel_room_no, webmail, alt_mail, date_of_admission, 
                father_name, mother_name, father_phone, mother_phone, 
                father_profession, mother_profession, annual_income, photo_path
            ) VALUES (
                ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?
            )";

    $params = [
        'ssssssssssssssssssssssssssssssss', 
        $roll_number, $full_name, $preferred_name, $dob, $gender, 
        $mother_tongue, $religion, $nationality, $state, $blood_group, 
        $category, $country_code, $mobile_no, $whatsapp_no, $course, 
        $department, $branch, $specialization, $hostel_name, $hostel_block, 
        $hostel_room_no, $webmail, $alt_mail, $date_of_admission, 
        $father_name, $mother_name, $father_phone, $mother_phone, 
        $father_profession, $mother_profession, $annual_income, $photo_path
    ];

    $query_result = execute_query($sql, $params);

    if ($query_result['success']) {
        echo "Registration successful!";
    } else {
        echo "Registration failed. Please try again.";
        // If registration fails, remove the uploaded photo
        unlink($photo_path);
    }
}
?>