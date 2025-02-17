<?php
require_once 'functions.php';

// Start session for validation
session_start();

if (!isset($_SESSION['emp_id'])) {
    die("Unauthorized access.");
}

$facMasterId = $_SESSION['emp_id'];

// Fetch course code and semester from GET request
$courseCode = $_GET['course_code'] ?? '';
$semester = 7; // You can modify this to fetch dynamically if needed.

if (!$courseCode || !$semester) {
    die("Course code or semester not provided.");
}

// Validate course access for the professor
$authQuery = "SELECT * FROM course_elective_mapping WHERE fac_masterid = ? AND course_code = ?";
$authParams = [$facMasterId, $courseCode];
if (!executeQuery($authQuery, $authParams, 'si', 'select')) {
    die("Access denied.");
}

// Initialize messages
$successMessage = '';
$errorMessage = '';

try {
    // Fetch enrolled students and their grades
    $query = "SELECT crt.roll, 'John Doe' as student_name, crt.grade1
                  FROM course_reg_table crt
                  WHERE crt.course_code = ? AND crt.sem = ?";
//     $query = "SELECT crt.roll, au.full_name as student_name, crt.grade1
//               FROM course_reg_table crt
//               INNER JOIN acad_users au ON crt.roll = au.roll_number
//               WHERE crt.course_code = ? AND crt.sem = ?";
    $params = [$courseCode, $semester];
    $params_type = 'si';
    $students = executeQuery($query, $params, $params_type, 'select'); // Fetch all rows
} catch (Exception $e) {
    die("Error fetching data: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $gradesToUpdate = $_POST['grades'] ?? [];

        foreach ($gradesToUpdate as $rollNo => $grade) {
            $grade = strtoupper(trim($grade));
            if ($grade === '') {
                $grade = null; // Set blank grades to NULL
            }

            // Update grade in the database
            $updateQuery = "UPDATE course_reg_table SET grade1 = ? WHERE roll = ? AND course_code = ?";
            $updateParams = [$grade, $rollNo, $courseCode];
            executeQuery($updateQuery, $updateParams, 'sss', 'update');
        }

        $successMessage = "Grades updated successfully!";
        // Re-fetch updated data
        $students = executeQuery($query, $params, $params_type, 'select');
    } catch (Exception $e) {
        $errorMessage = "Error updating grades: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Grade Submission</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="css/prof_course_filling.css">
</head>
<body>
<div id="demo">
    <h1>Grade Submission</h1>
    <h2>Please fill the grades for Course: <?= htmlspecialchars($courseCode) ?>, Semester: <?= htmlspecialchars($semester) ?></h2>

    <!-- Success and error messages -->
    <?php if ($successMessage): ?>
        <div class="alert alert-success"><?= htmlspecialchars($successMessage) ?></div>
    <?php elseif ($errorMessage): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
    <?php endif; ?>

    <button type="button" id="uploadCsvBtn" class="btn btn-secondary">Upload CSV</button>
        <input type="file" id="csvFileInput" accept=".csv" style="display: none;">

        <div id="mappingModal" class="modal" style="display: none;">
            <div class="modal-content">
                <h2>Configure CSV Mapping</h2>
                <div id="mappingOptions"></div>
                <button id="confirmMapping" class="btn btn-primary">Confirm Mapping</button>
            </div>
        </div>

    <!-- Responsive table -->
    <div class="table-responsive-vertical shadow-z-1">
        <form method="POST" action="">
            <table id="table" class="table table-hover table-mc-light-blue">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Roll</th>
                    <th>Grade</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td data-title="Name"><?= htmlspecialchars($student['student_name'] ?? 'N/A') ?></td>
                        <td data-title="Roll"><?= htmlspecialchars($student['roll']) ?></td>
                        <td data-title="Grade">
                            <input
                                type="text"
                                name="grades[<?= htmlspecialchars($student['roll']) ?>]"
                                value="<?= htmlspecialchars($student['grade1'] ?? '') ?>"
                                class="form-control"
                                maxlength="2"
                                pattern="[A-FN]{1,2}"
                                title="Enter a valid grade (A-F or NULL)"
                            >
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Submit Grades</button>
        </form>
    </div>
</div>
<script src="scripts/prof_course_filling.js"></script>
</body>
</html>
