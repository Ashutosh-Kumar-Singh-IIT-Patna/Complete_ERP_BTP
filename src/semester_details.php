<?php
require_once('functions.php');

// Retrieve POST data
$roll_no = $_POST['roll_no'];
$name = $_POST['name'];
$course = $_POST['course'];
$semno = $_POST['semno'];

// Query to fetch courses for the given roll_no and semno from course_glob_table
$query = "SELECT cg.course_code, cg.c, cg.grade, cg.type, cm.course_name, cm.`l-t-p`
          FROM course_global_table cg
          JOIN course_master cm ON cg.course_code = cm.course_code
          WHERE cg.roll = ? AND cg.sem = ?";
$params = [$roll_no, $semno];
$courses = executeQuery($query, $params, 'si', 'select');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semester Details</title>
    <link rel="stylesheet" href="css/spi-cpi.css">
</head>
<body>
<?php include 'nav.php'; ?>
<div class="container">
    <h1>Indian Institute of Technology Patna<br>Student Information System</h1>

    <table class="student-info">
        <tr>
            <td>Roll No.: <?= htmlspecialchars($roll_no) ?></td>
            <td>Name of Student: <?= htmlspecialchars($name) ?></td>
            <td>Discipline: <?= htmlspecialchars($course) ?></td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>L-T-P</th>
                <th>Credits</th>
                <th>Type</th>
                <th>Grade</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($courses as $course) : ?>
                <tr>
                    <td><?= htmlspecialchars($course['course_code']) ?></td>
                    <td><?= htmlspecialchars($course['course_name']) ?></td>
                    <td><?= htmlspecialchars($course['l-t-p']) ?></td>
                    <td><?= htmlspecialchars($course['c']) ?></td>
                    <td><?= htmlspecialchars($course['type']) ?></td>
                    <td><?= htmlspecialchars($course['grade']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
