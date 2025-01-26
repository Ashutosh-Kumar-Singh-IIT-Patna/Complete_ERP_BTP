<?php
require_once 'config.php';
require_once 'functions.php';

if(!isset($_SESSION['roll'])){
    header("Location: index.php");
}

function getSemesterNo($rollno) {
    $currentYear = date('Y');
    $currentMonth = date('m');
    $yearOfAdmission = '20' . substr($rollno, 0, 2);
    $yearsSinceAdmission = $currentYear - $yearOfAdmission;
    $semesterNo = ($yearsSinceAdmission * 2) + 1;

    if ($currentMonth < 6) {
        $semesterNo += 1;
    }

    return $semesterNo;
}
$roll = $_SESSION['roll']; // Assuming roll is stored in session
$sem_no = $_SESSION['sem_no']; // Assuming semester is stored in session

// Fetch registered course details using a JOIN between course_reg_table and course_master
$sql = "
    SELECT crt.course_code, cm.course_name, CONCAT(cm.`l-t-p`, '-', cm.c) as l_t_p_c
    FROM course_reg_table crt
    JOIN course_master cm ON crt.course_code = cm.course_code
    WHERE crt.roll = ? AND crt.sem = ?";
$registered_courses = executeQuery($sql, [$roll, $sem_no], 'si', 'select');

// Handle session messages
$messages = isset($_SESSION['MESSAGE']) ? $_SESSION['MESSAGE'] : [];
// Clear messages after displaying
unset($_SESSION['MESSAGE']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registered Courses</title>
    <style>
        .popup {
            position: fixed;
            top: 10%;
            left: 50%;
            transform: translateX(-50%);
            width: 50%;
            background-color: #f8f8f8;
            border: 1px solid #ccc;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 15px;
            z-index: 1000;
        }

        .popup .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            cursor: pointer;
            color: #999;
            font-size: 20px;
        }

        .popup .message {
            margin: 10px 0;
            padding: 10px;
            background-color: #e0e0e0;
            border-radius: 5px;
        }

        ul {
            list-style-type: none;
            padding: 0;
        }

        ul li {
            background-color: #f0f0f0;
            padding: 8px;
            margin-bottom: 5px;
            border-radius: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f8f8f8;
        }

        .info {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>


<!-- Display Name, Roll No, and Branch (random data for now) -->
<div class="info">
    <strong>Name:</strong> John Doe<br>
    <strong>Roll No:</strong> <?php echo htmlspecialchars($roll); ?><br>
    <strong>Branch:</strong> Computer Science
</div>

<?php if (!empty($registered_courses)): ?>
    <table>
        <thead>
            <tr>
                <th>Course Code</th>
                <th>Course Name</th>
                <th>L-T-P-C</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($registered_courses as $course): ?>
                <tr>
                    <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                    <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                    <td><?php echo htmlspecialchars($course['l_t_p_c']); ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php else: ?>
    <p>No courses registered yet.</p>
<?php endif; ?>

<!-- Popup for session messages -->
<?php if (!empty($messages)): ?>
    <div class="popup">
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        <?php foreach ($messages as $message): ?>
            <div class="message"><?php echo htmlspecialchars($message); ?></div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

</body>
</html>
