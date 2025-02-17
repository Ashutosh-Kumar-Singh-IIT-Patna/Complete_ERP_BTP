<?php
// process_payment.php
require_once 'config.php';
require_once 'functions.php';

$roll_number = $_POST['roll_number'];
$mode = $_POST['mode']; // 'loan' or 'direct'
$transaction_id = uniqid();
$status = 'Paid';

if ($mode === 'loan') {
    $receipt = $_FILES['receipt'];
    $receipt_path = "uploads/documents/{$roll_number}_loan_receipt_" . time() . ".pdf";
    move_uploaded_file($receipt['tmp_name'], $receipt_path);
} else {
    $receipt_path = '';
}

$sql = "UPDATE acad_fee SET status = ?, transaction_id = ?, mode_of_payment = ?, uploaded_document_path = ? WHERE roll_number = ?";
execute_query($sql, ['sssss', $status, $transaction_id, $mode, $receipt_path, $roll_number]);

echo json_encode(['success' => true, 'transaction_id' => $transaction_id]);
?>