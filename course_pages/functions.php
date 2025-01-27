<?php
require_once '../config.php';

/**
 * Logs detailed activity including errors, queries, and function calls.
 * Writes to both a log file and the database for critical activity.
 *
 * @param string $message The log message.
 * @param string $query Optional SQL query.
 * @param bool $isError Flag for error logs.
 */
function logActivity($message, $query = '', $isError = false) {
    // Get the caller information for trace logging
    $backtrace = debug_backtrace();
    $caller = isset($backtrace[1]['function']) ? $backtrace[1]['function'] : 'global';
    $line = isset($backtrace[0]['line']) ? $backtrace[0]['line'] : 'unknown';

    // Log formatting
    $logMessage = sprintf(
        "[%s] Function: %s (Line %d) | %s | Query: %s",
        date('Y-m-d H:i:s'),
        $caller,
        $line,
        $message,
        $query ? json_encode($query) : 'N/A'
    );

    // Write to log file
    error_log($logMessage . PHP_EOL, 3, LOG_FILE_PATH);
}

/**
 * Executes an SQL query securely with prepared statements.
 * Logs the query execution and any errors.
 * Can also handle transactions like 'begin', 'commit', and 'rollback'.
 *
 * @param string $query The SQL query to execute, or a transaction control command.
 * @param array $params The parameters for the query (optional).
 * @param string $types The parameter types (optional).
 * @param string $returnType Type of the query to determine the return value (e.g., 'select', 'insert', 'update', 'transaction').
 * @return mixed The result of the query or transaction control.
 * @throws Exception If the query execution fails.
 */
function executeQuery($query, $params = [], $types = '', $returnType = 'default') {
    $stmt = null;        // Initialize statement variable
    $conn = db_connect(); // Get database connection
    try {
        // Handle transaction control queries
        if (in_array($returnType, ['begin', 'commit', 'rollback'])) {
            switch ($returnType) {
                case 'begin':
                    if (!$conn->begin_transaction()) {
                        throw new Exception("Failed to begin transaction: " . $conn->error);
                    }
                    logActivity('Transaction started');
                    return true;
                case 'commit':
                    if (!$conn->commit()) {
                        throw new Exception("Failed to commit transaction: " . $conn->error);
                    }
                    logActivity('Transaction committed');
                    return true;
                case 'rollback':
                    if (!$conn->rollback()) {
                        throw new Exception("Failed to rollback transaction: " . $conn->error);
                    }
                    logActivity('Transaction rolled back');
                    return true;
            }
        }

        // Prepare the query
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Failed to prepare the query: " . $conn->error);
        }

        // Bind parameters if provided
        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }

        // Execute the statement
        if (!$stmt->execute()) {
            throw new Exception("Failed to execute the query: " . $stmt->error);
        }

        // Handle different return types
        switch ($returnType) {
            case 'select': // Fetch and return rows for SELECT queries
                $result = $stmt->get_result();
                if ($result === false) {
                    throw new Exception("Failed to get result: " . $stmt->error);
                }
                $data = $result->fetch_all(MYSQLI_ASSOC);
                logActivity('SELECT query executed successfully', $query);
                return $data;

            case 'insert': // Return the last inserted ID for INSERT queries
                $lastInsertId = $conn->insert_id;
                logActivity('INSERT query executed successfully', $query);
                return $lastInsertId;

            case 'update': // Return affected rows for UPDATE queries
                $affectedRows = $stmt->affected_rows;
                logActivity('UPDATE query executed successfully', $query . " | Affected Rows: $affectedRows");
                return $affectedRows;

            default: // For other queries, just confirm execution
                logActivity('Query executed successfully', $query);
                return true;
        }

    } catch (Exception $e) {
        // Log the error and rethrow
        logActivity('Query execution failed: ' . $e->getMessage(), $query, true);
        throw new Exception("Query failed: " . $e->getMessage());
    } finally {
        // Close statement and connection
        if ($stmt && $stmt instanceof mysqli_stmt) {
            $stmt->close();
        }
        if ($conn && $conn instanceof mysqli) {
            $conn->close();
        }
    }
}

/**
 * Validates the CSRF token to prevent CSRF attacks.
 * Ensures the CSRF token is correctly stored in the session and form submission.
 *
 * @return bool True if the token is valid, false otherwise.
 */
function validateCsrfToken() {
    return isset($_POST['csrf_token']) && $_POST['csrf_token'] === $_SESSION['csrf_token'];
}

/**
 * Sanitizes output to prevent XSS attacks.
 *
 * @param string $data The data to be sanitized.
 * @return string Sanitized data.
 */
function sanitizeOutput($data) {
    return htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
}
?>
