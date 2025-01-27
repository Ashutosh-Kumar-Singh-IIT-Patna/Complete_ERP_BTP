<?php
require_once 'functions.php';

// Fetch course code from GET request
$courseCode = $_GET['course_code'] ?? '';
$semester = 7;

if (!$courseCode || !$semester) {
    die("Course code or semester not provided.");
}

// Initialize messages
$successMessage = '';
$errorMessage = '';

try {
    // Fetch enrolled students and their grades
    $query = "SELECT roll, grade1 FROM course_reg_table WHERE course_code = ? AND sem = ?";
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
            $updateQuery = "UPDATE course_reg_table SET grade = ? WHERE roll_no = ? AND course_code = ?";
            $updateParams = [$grade, $rollNo, $courseCode];
            executeQuery($updateQuery, $updateParams);
        }

        $successMessage = "Grades updated successfully!";
        // Re-fetch updated data
        $students = executeQuery($query, $params, true);
    } catch (Exception $e) {
        $errorMessage = "Error updating grades: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Professor Grades</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js" type="text/javascript"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
    <link rel="stylesheet" href="./style.css">
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

    <!-- Responsive table starts here -->
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
                        <td data-title="Name">John Doe</td>
                        <td data-title="Roll"><?= htmlspecialchars($student['roll']) ?></td>
                        <td data-title="Grade">
                            <input
                                type="text"
                                name="grades[<?= htmlspecialchars($student['roll_no']) ?>]"
                                value="<?= htmlspecialchars($student['grade'] ?? '') ?>"
                                class="form-control"
                                maxlength="2"
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
</body>
</html>
