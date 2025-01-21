<?php
session_start();    
require_once '../functions.php';

$roll = $_SESSION['roll'] ?? null;

if (!$roll) {
    die("Error: User not logged in.");
}

// Fetch full name from the database
$sql = "SELECT full_name FROM acad_users WHERE roll_number = ?";
$result = execute_query($sql, ['s', $roll]);

if (!$result['success'] || $result['result']->num_rows == 0) {
    die("Error: Invalid roll number.");
}

$full_name = $result['result']->fetch_assoc()['full_name'];

// Create the uploads/documents directory if it doesn't exist
$upload_dir = __DIR__ . '/uploads/documents/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0777, true);
}

// Helper function to generate a random string
function generate_random_string($length = 6) {
    return bin2hex(random_bytes($length / 2));
}

// File upload logic
$document_types = ['aadhar_card', 'marksheet_10th', 'marksheet_12th'];
$errors = [];
$success = [];

foreach ($document_types as $document) {
    if (isset($_FILES[$document])) {
        $file = $_FILES[$document];

        if ($file['error'] === 0 && $file['size'] <= 5 * 1024 * 1024) { // 5MB limit
            $file_ext = pathinfo($file['name'], PATHINFO_EXTENSION);
            $new_name = sprintf(
                '%s_%s_%s_%s_%s.%s',
                $roll,
                str_replace(' ', '_', strtoupper($full_name)),
                strtoupper($document),
                generate_random_string(),
                $file_ext
            );

            $destination = $upload_dir . $new_name;

            if (move_uploaded_file($file['tmp_name'], $destination)) {
                $success[] = "$document uploaded successfully.";
            } else {
                $errors[] = "Failed to upload $document.";
            }
        } else {
            $errors[] = "$document must be a PDF and less than 5MB.";
        }
    }
}

// Display success or error messages
if (!empty($errors)) {
    foreach ($errors as $error) {
        echo "<p style='color: red;'>$error</p>";
    }
} else {
    echo "<p style='color: green;'>All documents uploaded successfully.</p>";
}

?>
