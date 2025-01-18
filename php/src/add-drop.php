<?php
session_start();
require_once 'config.php'; // For database connection
require_once 'functions.php'; // For helper functions

if (!isset($_SESSION['roll'])) {
    header("Location: index.php");
    exit;
}

$roll = $_SESSION['roll'];
$sem = $_SESSION['sem_no'];

// Fetch registered courses (HS/DE/IDE)
$sql_registered = "
    SELECT cr.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits, cr.type
    FROM course_reg_table cr
    JOIN course_master cm ON cr.course_code = cm.course_code
    WHERE cr.roll = ? AND cr.sem = ? AND cr.type IN ('HS%', 'DE%', 'IDE%')";
$registered_courses = executeQuery($sql_registered, [$roll, $sem], 'si', 'select');

// Initialize available courses
$available_courses = [];

// Fetch available courses for each type
foreach ($registered_courses as $course) {
    $type = $course['type'];

    if (strpos($type, 'IDE') === 0) {
        // Handle IDE courses (special case)
        $roll_prefix_4 = substr($roll, 0, 4);
        $roll_prefix_6 = substr($roll, 0, 6);

        $sql_available = "
            SELECT cem.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits
            FROM course_elective_mapping cem
            JOIN course_master cm ON cem.course_code = cm.course_code
            WHERE cem.type LIKE 'IDE%'
            AND cem.floated = 1
            AND cem.sem = ?
            AND cem.roll LIKE CONCAT(?, '%')
            AND cem.roll NOT LIKE CONCAT(?, '%')";
        $available_courses[$type] = executeQuery($sql_available, [$sem, $roll_prefix_4, $roll_prefix_6], 'iss', 'select');
    } else {
        // Handle HS/DE courses
        $sql_available = "
            SELECT cem.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits
            FROM course_elective_mapping cem
            JOIN course_master cm ON cem.course_code = cm.course_code
            WHERE cem.type = ?
            AND cem.floated = 1
            AND cem.sem = ?";
        $available_courses[$type] = executeQuery($sql_available, [$type, $sem], 'si', 'select');
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add/Drop Courses</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f2f2f2;
        }
        .action-btn {
            padding: 5px 10px;
            color: white;
            border: none;
            cursor: pointer;
        }
        .drop-btn {
            background-color: red;
        }
        .add-btn {
            background-color: green;
        }
    </style>
</head>
<body>
    <h1>Manage Your Elective Courses</h1>
    <form method="post" action="process_add_drop.php">
        <table>
            <thead>
                <tr>
                    <th>Type</th>
                    <th>Registered Course</th>
                    <th>Drop</th>
                    <th>Available Courses</th>
                    <th>Add</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($registered_courses as $course): ?>
                    <tr>
                        <td><?= $course['type'] ?></td>
                        <td>
                            <?= $course['course_name'] ?> (<?= $course['course_code'] ?>)<br>
                            L-T-P: <?= $course['ltp'] ?> | Credits: <?= $course['credits'] ?>
                        </td>
                        <td>
                            <button type="submit" name="drop_course" value="<?= $course['course_code'] ?>" class="action-btn drop-btn">
                                Drop
                            </button>
                        </td>
                        <td>
                            <select name="add_course_<?= $course['type'] ?>">
                                <option value="" disabled selected>Select a course</option>
                                <?php foreach ($available_courses[$course['type']] as $available): ?>
                                    <option value="<?= $available['course_code'] ?>">
                                        <?= $available['course_name'] ?> (<?= $available['course_code'] ?>)<br>
                                        L-T-P: <?= $available['ltp'] ?> | Credits: <?= $available['credits'] ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <button type="submit" name="add_course_btn_<?= $course['type'] ?>" class="action-btn add-btn">
                                Add
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </form>
</body>
</html>
