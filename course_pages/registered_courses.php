<?php
require_once 'functions.php';
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!isset($_SESSION['roll'])){
    header("Location: index.php");
}
function getStudentName($rollno) {
    $sql = "SELECT full_name, department FROM acad_users WHERE roll_number = ?";
    $result = executeQuery($sql, [$rollno], 's', 'select');
    return $result;
}
$result = getStudentName($_SESSION['roll']);
$name = $result[0]['full_name'] ?? 'Unknown Student';   
$department = $result[0]['department'] ?? 'Unknown Department'; // Assuming department is fetched from the database

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
    <link rel="stylesheet" href="css/registered_courses.css">
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

        .popup {
        position: fixed;
        top: 10%;
        left: 50%;
        transform: translateX(-50%);
        width: 50%;
        background-color: #fff;
        border: 1px solid #ccc;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        padding: 15px;
        z-index: 1000;
        border-radius: 5px;
        }

        .popup .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        cursor: pointer;
        color: #888;
        font-size: 20px;
        }

        .popup .message {
        margin: 10px 0;
        padding: 10px;
        background-color: #f1f8ff;
        border-left: 5px solid #007bff;
        border-radius: 5px;
        color: #0056b3;
        }
    </style>
</head>
<body>
<?php include 'nav.php'; ?>


<div class="info">
    <strong>Name:</strong> <?php echo htmlspecialchars($name); ?><br>
    <strong>Roll No:</strong> <?php echo htmlspecialchars($roll); ?><br>
    <strong>Branch:</strong> <?php echo htmlspecialchars($department); ?><br>
</div>

<?php if (!empty($registered_courses)): ?>
    <table class="container">
        <thead>
            <tr>
                <th><h1>Course Code</h1></th>
                <th><h1>Course Name</h1></th>
                <th><h1>L-T-P-C</h1></th>
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