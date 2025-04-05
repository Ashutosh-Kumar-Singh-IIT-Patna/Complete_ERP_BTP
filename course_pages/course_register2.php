<?php

require_once 'functions.php';

session_start();
// Class to hold course details
class Details {
    public $course_code;
    public $course_name;
    public $l_t_p;
    public $c;
    public $max_capacity;

    public function __construct($course_code, $course_name, $l_t_p, $c, $max_capacity) {
        $this->course_code = $course_code;
        $this->course_name = $course_name;
        $this->l_t_p = $l_t_p;
        $this->c = $c;
        $this->max_capacity = $max_capacity;
    }
}
if(!isset($_SESSION['roll']) || !isset($_SESSION['sem_no'])) {
    header("Location: index.php");
}
$roll = $_SESSION['roll']; 
$sem_no = $_SESSION['sem_no'];
$rollPref = substr($roll, 0, 6);
$mp = [];

// function getStudentName($rollno) {
//     $sql = "SELECT full_name, department FROM acad_users WHERE roll_number = ?";
//     $result = executeQuery($sql, [$rollno], 'ss', 'select');
//     return $result;
// }
// $result = getStudentName($_SESSION['roll']);
// $name = isset($result[0]['full_name']) ? $result[0]['full_name'] : null;
// $department = isset($result[0]['department']) ? $result[0]['department'] : null;

// SQL for core courses (Type C)
$sql = "
SELECT course_core_mapping.course_code as course_code, course_master.course_name as course_name, course_master.`l-t-p` as l_t_p, course_master.c as c
FROM course_core_mapping
JOIN course_master ON course_core_mapping.course_code = course_master.course_code
WHERE course_core_mapping.roll = ? AND course_core_mapping.sem = ? AND course_core_mapping.floated = 1";

$core_courses = executeQuery($sql, [$rollPref, $sem_no], 'si', 'select');

foreach ($core_courses as $row) {
    $mp['C'][] = new Details($row['course_code'], $row['course_name'], $row['l_t_p'], $row['c'], NULL);
}

// SQL for elective courses (HS/DE)
$sql = "SELECT sem_{$sem_no} FROM course_structure WHERE roll = ?";
$str_res = executeQuery($sql, [$rollPref], 's', 'select');
$course_types = explode(',', $str_res[0]["sem_{$sem_no}"]);
// Filter for HS% and DE%, excluding IDE%
$elective_types = array_filter($course_types, fn($type) => preg_match('/HS|DE/', $type) && !preg_match('/IDE/', $type));

foreach ($elective_types as $type) {
    $sql = "
    SELECT course_elective_mapping.course_code as course_code, course_master.course_name as course_name, course_master.`l-t-p` as l_t_p, course_master.c as c, course_elective_mapping.max_capacity as max_capacity
    FROM course_elective_mapping
    JOIN course_master ON course_elective_mapping.course_code = course_master.course_code
    WHERE course_elective_mapping.type = ? AND course_elective_mapping.floated = 1 and course_elective_mapping.sem = ? and course_elective_mapping.roll = ?";
    $electives = executeQuery($sql, [$type, $sem_no, $rollPref], 'sis', 'select');
    foreach ($electives as $course) {
        $mp[$type][] = new Details($course['course_code'], $course['course_name'], $course['l_t_p'], $course['c'], $course['max_capacity']);
    }
}

$has_ide = array_filter($course_types, fn($type) => preg_match('/^IDE/', $type));

// Open elective (IDE) handling
if (!empty($has_ide)) { // Check if there are any IDE types
    $rollp = substr($roll, 0, 4); // First 4 characters of the roll number
    $rollPref = substr($roll, 0, 6); // First 6 characters of the roll number

    // Fetch courses that match roll prefix (4 chars), sem, and type IDE%
    $sql = "
    SELECT cem.course_code as course_code,
           cm.course_name as course_name,
           cm.`l-t-p` as l_t_p,
           cm.c as c,
           cem.max_capacity as max_capacity
    FROM course_elective_mapping cem
    JOIN course_master cm ON cem.course_code = cm.course_code
    WHERE cem.type LIKE 'IDE%'
      AND cem.floated = 1
      AND cem.sem = ?
      AND cem.roll LIKE CONCAT(?, '%')";

    $ide_courses = executeQuery($sql, [$sem_no, $rollp], 'is', 'select');

    // Fetch all course codes matching the first 6 characters of the roll
    $sql2 = "
    SELECT course_code
    FROM course_elective_mapping
    WHERE roll LIKE CONCAT(?, '%')
      AND sem = ?
      AND type LIKE 'IDE%'
      AND floated = 1";
    $branch_courses = executeQuery($sql2, [$rollPref, $sem_no], 'si', 'select');

    // Get an array of course codes from the second query
    $branch_course_codes = array_column($branch_courses, 'course_code');

    // Filter the first list to exclude any course_codes that are in the second list
    $final_res = array_filter($ide_courses, fn($course) => !in_array($course['course_code'], $branch_course_codes));

    // Process the final list
    foreach ($final_res as $course) {
        $mp['IDE'][] = new Details($course['course_code'], $course['course_name'], $course['l_t_p'], $course['c'], $course['max_capacity']);
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = db_connect();
    $db->begin_transaction(); // Start the transaction

    try {
        $selected_courses = $_POST['selected_courses'];
        $_SESSION['MESSAGE'] = [];

        // Check if the student has already registered any course for this semester
        $check_registration_sql = "SELECT COUNT(*) AS course_count FROM course_reg_table WHERE roll = ? AND sem = ?";
        $existing_courses = executeQuery($check_registration_sql, [$roll, $sem_no], 'si', 'select');

        if ($existing_courses[0]['course_count'] > 0) {
            // If any course is already registered for this semester
            $_SESSION['MESSAGE'][] = "You have already registered courses for this semester.";
            throw new Exception("Student already registered");
        }

        // Now check if all elective courses can be registered
        foreach ($selected_courses as $type => $course_code) {
            if ($type !== 'C') { // Only check capacity for elective courses
                // Find course details in mp
                $course_details = array_filter($mp[$type], fn($course) => $course->course_code === $course_code);

                if (empty($course_details)) {
                    continue; // Skip if no details found
                }

                $course_details = reset($course_details); // Get the first element

                // Check capacity
                $sql = "SELECT COUNT(*) AS enrolled_students FROM course_reg_table WHERE course_code = ?";
                $capacity_check = executeQuery($sql, [$course_code], 's', 'select');
                $enrolled_students = $capacity_check[0]['enrolled_students'];

                if ($course_details->max_capacity && $enrolled_students >= $course_details->max_capacity) {
                    // If any elective course exceeds capacity, raise message and stop the process
                    $_SESSION['MESSAGE'][] = "{$course_details->course_name} has reached maximum capacity. Registration failed.";
                    throw new Exception("Capacity exceeded");
                }
            }
        }

        // If all checks pass, insert core courses
        foreach ($mp['C'] as $core_course) {
            logActivity("Roll: " . $roll);
            logActivity("Semester: " . $sem_no);
            logActivity("Course Code: " . $core_course->course_code);
            logActivity("C value: " . $core_course->c);
            $sql_insert = "
            INSERT INTO course_reg_table (roll, sem, course_code, c, type, date_of_entry)
            VALUES (?, ?, ?, ?, 'C', NOW())";
            $params = [$roll, $sem_no, $core_course->course_code, $core_course->c];
            executeQuery($sql_insert, $params, 'sisi', 'insert');
        }

        // Insert elective courses
        foreach ($selected_courses as $type => $course_code) {
            $course_details = array_filter($mp[$type], fn($course) => $course->course_code === $course_code);
            if (empty($course_details)) continue;
            $course_details = reset($course_details); // Get the first element

            if ($type !== 'C') { // Only insert electives
                $sql_insert = "
                INSERT INTO course_reg_table (roll, sem, course_code, c, type, date_of_entry)
                VALUES (?, ?, ?, ?, ?, NOW())";
                $params = [$roll, $sem_no, $course_code, $course_details->c, $type];
                executeQuery($sql_insert, $params, 'sisis', 'insert');
            }
        }

        // Commit transaction if everything is successful
        $db->commit();

    } catch (Exception $e) {
        // Rollback if any error occurs
        $db->rollback();
        if (!isset($_SESSION['MESSAGE'])) {
            $_SESSION['MESSAGE'][] = "There is a technical error from the server. Please try again later.";
        }
    } finally {
        $db->close(); // Close the connection
    }

    // Redirect to registered_courses.php
    header("Location: registered_courses.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Course Registration</title>
    <link rel="stylesheet" href="css/course_register.css">
    <style>
        .info {
        width: 85%;
        margin: 20px auto;
        padding: 15px;
        background-color: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        color: #555;
        font-size: 0.95em;
        }

        .info strong {
        color: #333;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>
<h1>Course Registration</h1>

<form method="post">
    <!-- <div class="info">
        <strong>Name:</strong> <?php echo htmlspecialchars($name); ?><br>
        <strong>Roll No:</strong> <?php echo htmlspecialchars($roll); ?><br>
        <strong>Branch:</strong> <?php echo htmlspecialchars($department); ?><br>
    </div> -->
    <div class="course-container">
        <?php
        // Display core courses (no capacity check)
        if (!empty($mp['C'])) {
            foreach ($mp['C'] as $course) {
                echo '<div class="course-card">';
                echo "<div class='course-title'>{$course->course_code}: {$course->course_name}</div>";
                echo "<div class='course-details'>L-T-P-C: {$course->l_t_p}-{$course->c}</div>";
                echo '</div>';
            }
        }

        // Display elective courses (HS/DE) in dropdown
        foreach ($mp as $type => $courses) {
            if ($type !== 'C') {
                echo '<div class="elective-card">';
                echo "<h4>Select $type course:</h4>";
                echo '<div class="custom-dropdown">';
                echo "<select name='selected_courses[$type]'>";
                foreach ($courses as $course) {
                    echo "<option value='{$course->course_code}'>{$course->course_code}: {$course->course_name} (L-T-P-C: {$course->l_t_p}-{$course->c})</option>";
                }
                echo '</select>';
                echo '</div>';
                echo '</div>';
            }
        }
        ?>
    </div>

    <input type="submit" class="submit-btn" value="Submit">
</form>
</body>
</html>
