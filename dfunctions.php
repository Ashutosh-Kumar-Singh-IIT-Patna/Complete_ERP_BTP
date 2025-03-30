<?php
// functions.php
require_once 'config.php';

$conn = db_connect();

function execute_query($sql, $params = []) {
    global $conn;
    
    // Prepare the statement
    $stmt = $conn->prepare($sql);
    if ($stmt === false) {
        error_log("SQL Prepare Error: " . $conn->error . "\nSQL: $sql", 3, LOG_FILE_PATH);
        return ['success' => false, 'error' => $conn->error];
    }

    // Bind parameters if they exist
    if (!empty($params)) {
        $stmt->bind_param(...$params);
    }

    try {
        $success = $stmt->execute();
        if (!$success) {
            error_log("SQL Execute Error: " . $stmt->error, 3, LOG_FILE_PATH);
            return ['success' => false, 'error' => $stmt->error];
        }

        $result = $stmt->get_result();
        $data = $result ? $result->fetch_all(MYSQLI_ASSOC) : null; // Fetch data properly

        // Log query in the database
        $traceback = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS);
        $trace_str = print_r($traceback, true);
        $log_sql = "INSERT INTO acad_queries_log (query, traceback) VALUES (?, ?)";
        $log_stmt = $conn->prepare($log_sql);
        $log_stmt->bind_param("ss", $sql, $trace_str);
        $log_stmt->execute();

        // Log query in a file
        error_log("Executed Query: $sql\nTraceback: $trace_str", 3, LOG_FILE_PATH);

        return ['success' => true, 'data' => $data];

    } catch (Exception $e) {
        error_log("Exception: " . $e->getMessage(), 3, LOG_FILE_PATH);
        return ['success' => false, 'error' => $e->getMessage()];
    }
}

function calculateDuration($startDate, $endDate) {
    // If either date is invalid ('-'), return '-'
    if ($startDate === '-' || $endDate === '-') {
        return '-';
    }

    try {
        $start = new DateTime($startDate);
        $end = new DateTime($endDate);
        $interval = $start->diff($end);

        return "{$interval->y} years, {$interval->m} months";
    } catch (Exception $e) {
        return '-';
    }
}

function formatDate($date) {
    $timestamp = strtotime($date);
    return $timestamp ? date("d-M-Y", $timestamp) : '-';
}
?>
