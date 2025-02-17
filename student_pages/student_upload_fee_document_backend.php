<?php
// upload_document.php
require_once '../functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $roll_number = $_POST['roll_number'];
    $semester = $_POST['semester'];
    $file = $_FILES['document'];
    
    $timestamp = time();
    $filename = "uploads/fee_documents/{$roll_number}_{$semester}_{$timestamp}.pdf";

    if (move_uploaded_file($file['tmp_name'], $filename)) {
        echo json_encode(['success' => true, 'path' => $filename]);
    } else {
        echo json_encode(['error' => 'Upload failed']);
    }
}
?>