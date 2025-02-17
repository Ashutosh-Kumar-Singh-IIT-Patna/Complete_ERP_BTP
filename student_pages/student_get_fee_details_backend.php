<?php
// get_fee_details.php
require_once '../functions.php';

$roll_number = $_GET['roll_number'] ?? '';

if (!$roll_number) {
    echo json_encode(['error' => 'Roll number required']);
    exit;
}

$sql = "SELECT * FROM acad_fee WHERE roll_number = ?";
$response = execute_query($sql, ['s', $roll_number]);

if ($response['success'] && $response['result']->num_rows > 0) {
    echo json_encode($response['result']->fetch_assoc());
} else {
    echo json_encode(['error' => 'No records found']);
}
?>