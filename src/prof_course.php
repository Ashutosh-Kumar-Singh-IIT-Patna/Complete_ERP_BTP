<?php
require_once("functions.php");

$_SESSION['emp_id'] = 54;
$emp_id = $_SESSION['emp_id'];
$current_sem = $_SESSION['sem'];

// Query to fetch courses taught by the professor
$query_courses = "
    SELECT
        cm.course_code,
        cm.course_name,
        cm.`l-t-p`,
        cm.c
    FROM
        course_master cm
    WHERE
        cm.course_code IN (
            SELECT course_code
            FROM course_core_mapping
            WHERE fac_masterid = ?
            UNION
            SELECT course_code
            FROM course_elective_mapping
            WHERE fac_masterid = ?
        )
";
$params_courses = [$emp_id, $emp_id];
$types_courses = 'ii';

$courses_data = executeQuery($query_courses, $params_courses, $types_courses, 'select');

$courses = [];
if ($courses_data) {
    foreach ($courses_data as $row) {
        $course_code = $row['course_code'];

        // Fetch total students
        $query_total_students = "
            SELECT COUNT(*) AS total_students
            FROM course_reg_table
            WHERE course_code = ? AND sem = ?
        ";
        $params_total_students = [$course_code, $current_sem];
        $types_total_students = 'si';

        $total_students_data = executeQuery($query_total_students, $params_total_students, $types_total_students, 'select');
        $total_students = $total_students_data[0]['total_students'] ?? 0;

        // Fetch graded students
        $query_graded_students = "
            SELECT COUNT(*) AS graded_students
            FROM course_reg_table
            WHERE course_code = ? AND sem = ? AND grade1 IS NOT NULL
        ";
        $params_graded_students = [$course_code, $current_sem];
        $types_graded_students = 'si';

        $graded_students_data = executeQuery($query_graded_students, $params_graded_students, $types_graded_students, 'select');
        $graded_students = $graded_students_data[0]['graded_students'] ?? 0;

        // Calculate progress
        $progress = $total_students > 0 ? round(($graded_students / $total_students) * 100, 2) : 0;

        // Add course details to array
        $courses[] = [
            'course_code' => $course_code,
            'course_name' => $row['course_name'],
            'l_t_p' => $row['l-t-p'],
            'c' => $row['c'],
            'progress' => $progress,
        ];
    }
}

// Function to determine the current term
function getCurrentTerm() {
    $currentMonth = date('n');
    $currentYear = date('Y');
    return ($currentMonth >= 1 && $currentMonth <= 6) ? "Spring $currentYear" : "Autumn $currentYear";
}

$currentTerm = getCurrentTerm();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Professor Dashboard</title>
    <link rel="stylesheet" href="styles.css"> <!-- Add your CSS file -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .container {
            padding: 20px;
        }

        .card {
            background-color: #f5f5f5;
            border: 1px solid #ccc;
            border-radius: 10px;
            padding: 20px;
            margin: 10px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.2s;
        }

        .card:hover {
            transform: scale(1.02);
            cursor: pointer;
        }

        .card h3 {
            margin: 0;
            font-size: 1.5em;
            color: #333;
        }

        .card p {
            margin: 5px 0;
            color: #666;
        }

        .card-footer {
            margin-top: 10px;
            color: #555;
            font-size: 0.9em;
            display: flex;
            justify-content: space-between;
        }

        .progress-bar {
            background-color: #007bff;
            height: 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .progress-container {
            background-color: #ddd;
            border-radius: 5px;
            overflow: hidden;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Professor Dashboard</h1>
    <p>Term: <?php echo $currentTerm; ?></p>

    <?php foreach ($courses as $course): ?>
        <div class="card" onclick="window.location.href='course_filling.php?course_code=<?php echo $course['course_code']; ?>'">
            <h3><?php echo $course['course_name']; ?></h3>
            <p>Course Code: <?php echo $course['course_code']; ?></p>
            <div class="progress-container">
                <div class="progress-bar" style="width: <?php echo $course['progress']; ?>%;"></div>
            </div>
            <p>Progress: <?php echo $course['progress']; ?>%</p>
            <div class="card-footer">
                <span>L-T-P: <?php echo $course['l_t_p']; ?></span>
                <span>C: <?php echo $course['c']; ?></span>
            </div>
        </div>
    <?php endforeach; ?>

</div>
</body>
</html>
