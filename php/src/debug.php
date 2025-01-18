<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the functions.php file
require_once 'functions.php';

// Debug Session Variables
echo "<h2>Session Variables</h2>";
if (!empty($_SESSION)) {
    echo "<pre>" . print_r($_SESSION, true) . "</pre>";
} else {
    echo "No session variables set.<br>";
}

// Debugging Registered Courses
echo "<h2>Registered Courses</h2>";
try {
    $roll = $_SESSION['roll'] ?? null;
    $sem = $_SESSION['sem_no'] ?? null;

    if ($roll && $sem) {
        $query = "
            SELECT cr.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits, cr.type
            FROM course_reg_table cr
            JOIN course_master cm ON cr.course_code = cm.course_code
            WHERE cr.roll = ? AND cr.sem = ?
            AND (cr.type LIKE 'HS%' OR cr.type LIKE 'DE%' OR cr.type LIKE 'IDE%')";

        $params = [$roll, $sem];
        $types = 'si';

        // Debug Query
        echo "<h3>Debug Query</h3>";
        echo "Query: " . $query . "<br>";
        echo "Parameters: " . implode(', ', $params) . "<br>";

        $registeredCourses = executeQuery($query, $params, $types, 'select');

        if ($registeredCourses) {
            echo "<pre>" . print_r($registeredCourses, true) . "</pre>";
        } else {
            echo "No registered courses found.<br>";
        }
    } else {
        echo "Session variables 'roll' and 'sem_no' are not set.<br>";
    }
} catch (Exception $e) {
    echo "Error fetching registered courses: " . $e->getMessage() . "<br>";
}

// Debugging Available Courses
echo "<h2>Available Courses</h2>";
try {
    $type = 'IDE'; // Example type; adjust as needed

    if ($roll && $sem) {
        $query = "
            SELECT cem.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits
            FROM course_elective_mapping cem
            JOIN course_master cm ON cem.course_code = cm.course_code
            WHERE cem.type LIKE CONCAT(?, '%')
            AND LEFT(cem.roll, 4) = LEFT(?, 4)
            AND LEFT(cem.roll, 6) != LEFT(?, 6)";

        $params = [$type, $roll, $roll];
        $types = 'sss';
        $availableCourses = executeQuery($query, $params, $types, 'select');

        if ($availableCourses) {
            echo "<pre>" . print_r($availableCourses, true) . "</pre>";
        } else {
            echo "No available courses found for type $type.<br>";
        }
    } else {
        echo "Session variables 'roll' and 'sem_no' are not set.<br>";
    }
} catch (Exception $e) {
    echo "Error fetching available courses: " . $e->getMessage() . "<br>";
}
?>
