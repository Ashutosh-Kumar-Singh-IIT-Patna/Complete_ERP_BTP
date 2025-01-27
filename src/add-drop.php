<?php
require_once 'config.php'; // Database connection
require_once 'functions.php'; // Helper functions

if (!isset($_SESSION['roll']) || !isset($_SESSION['sem_no'])) {
    header("Location: index.php");
    exit;
}

$roll = $_SESSION['roll'];
$sem = $_SESSION['sem_no'];

// Check if the user has registered for courses
$sql_check_registration = "SELECT COUNT(*) AS count FROM course_reg_table WHERE roll = ? AND sem = ?";
$params_check_registration = [$roll, $sem];
$types_check_registration = 'si';
$registered_count = executeQuery($sql_check_registration, $params_check_registration, $types_check_registration, 'select')[0]['count'];

if ($registered_count == 0) {
    $_SESSION['message'] = "You have to register for courses";
    header("Location: course_register2.php");
    exit;
}

// Fetch registered courses (HS/DE/IDE only)
$sql_registered = "SELECT cr.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits, cr.type
                   FROM course_reg_table cr
                   JOIN course_master cm ON cr.course_code = cm.course_code
                   WHERE cr.roll = ? AND cr.sem = ?
                   AND (cr.type LIKE 'HS%' OR cr.type LIKE 'IDE%' OR cr.type LIKE 'DE%')";
$params_registered = [$roll, $sem];
$types_registered = 'si';
$registered_courses = executeQuery($sql_registered, $params_registered, $types_registered, 'select');

// Initialize available courses
$available_courses = [];

// Fetch available courses for each type
foreach ($registered_courses as $course) {
    $type = $course['type'];
//       echo '<pre>';
//           print_r($type);
//       echo '</pre>';
    if (strpos($type, 'IDE') === 0) {
        // Handle IDE courses
        $roll_prefix_4 = substr($roll, 0, 4);
        $roll_prefix_6 = substr($roll, 0, 6);

        $sql_available_ide = "
            SELECT cem.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits
            FROM course_elective_mapping cem
            JOIN course_master cm ON cem.course_code = cm.course_code
            WHERE cem.type LIKE 'IDE%'
            AND cem.floated = 1
            AND cem.sem = ?
            AND cem.roll LIKE CONCAT(?, '%')
            AND cem.roll NOT LIKE CONCAT(?, '%')";
        $params_available_ide = [$sem, $roll_prefix_4, $roll_prefix_6];
        $types_available_ide = 'iss';
        $available_courses[$type] = executeQuery($sql_available_ide, $params_available_ide, $types_available_ide, 'select');
    } else {
        // Handle HS/DE courses
        $roll_prefix_6 = substr($roll, 0, 6);

        // SQL query for HS/DE courses
        $sql_available_hs_de = "
            SELECT cem.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits
            FROM course_elective_mapping cem
            JOIN course_master cm ON cem.course_code = cm.course_code
            WHERE cem.type = ?
            AND cem.floated = 1
            AND cem.sem = ?
            AND cem.roll = ?";

        // Params for the query
        $params_available_hs_de = [$type, $sem, $roll_prefix_6];
        $types_available_hs_de = 'sis';

        // Debugging output before executing the query
//         echo "<pre>SQL: $sql_available_hs_de</pre>";
//         echo "<pre>Params: " . print_r($params_available_hs_de, true) . "</pre>";
//         echo "<pre>Types: $types_available_hs_de</pre>";

        $available_courses[$type] = executeQuery($sql_available_hs_de, $params_available_hs_de, $types_available_hs_de, 'select');

        // Debugging output after executing the query
        //echo "<pre>Available Courses: " . print_r($available_courses[$type], true) . "</pre>";
    }
}

// echo '<pre>';
//           print_r($available_courses);
//       echo '</pre>';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_drop_request'])) {
    $selected_course = $_POST['available_course'];
    $current_course = $_POST['current_course'];

    // Insert the add-drop request into the database
    $sql_request = "INSERT INTO requests (roll, sem, drop_course_code, add_course_code, status) VALUES (?, ?, ?, ?, 0)";
    executeQuery($sql_request, [$roll, $sem, $current_course, $selected_course], 'siss', 'insert');

    $_SESSION['MESSAGE'] = "Your request has been submitted successfully.";
    header("Location: registered_courses.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add-Drop Form</title>
    <link rel="stylesheet">
    <style>
        .elective-container {
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        table th {
            background-color: #f2f2f2;
            font-weight: bold;
        }

        .submit-btn {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .submit-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<?php include './../student_pages/nav.html'; ?>

<h1>Add-Drop Form</h1>

<?php if (!empty($_SESSION['message'])): ?>
    <div class="popup">
        <span class="close-btn" onclick="this.parentElement.style.display='none';">&times;</span>
        <div class="message"><?php echo htmlspecialchars($_SESSION['message']); unset($_SESSION['message']); ?></div>
    </div>
<?php endif; ?>

<?php foreach ($registered_courses as $index => $course): ?>
    <?php $type = $course['type']; ?>
    <?php if (in_array(substr($type, 0, 2), ['HS', 'DE', 'ID'])): ?>
        <form method="post">
            <div class="elective-container">
                <h2><?php echo htmlspecialchars($type); ?> Electives</h2>
                <div class="registered-courses">
                    <h3>Your Registered Course</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Drop</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>L-T-P</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="radio" name="current_course" value="<?php echo htmlspecialchars($course['course_code']); ?>" required>
                                </td>
                                <td><?php echo htmlspecialchars($course['course_code']); ?></td>
                                <td><?php echo htmlspecialchars($course['course_name']); ?></td>
                                <td><?php echo htmlspecialchars($course['ltp']); ?></td>
                                <td><?php echo htmlspecialchars($course['credits']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="available-courses">
                    <h3>Available Courses</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Add</th>
                                <th>Course Code</th>
                                <th>Course Name</th>
                                <th>L-T-P</th>
                                <th>Credits</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($available_courses[$type] as $available_course): ?>
                                <tr>
                                    <td>
                                        <input type="radio" name="available_course" value="<?php echo htmlspecialchars($available_course['course_code']); ?>" required>
                                    </td>
                                    <td><?php echo htmlspecialchars($available_course['course_code']); ?></td>
                                    <td><?php echo htmlspecialchars($available_course['course_name']); ?></td>
                                    <td><?php echo htmlspecialchars($available_course['ltp']); ?></td>
                                    <td><?php echo htmlspecialchars($available_course['credits']); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <input type="hidden" name="elective_type" value="<?php echo htmlspecialchars($type); ?>">
                <button type="submit" name="add_drop_request" class="submit-btn">Submit Add-Drop Request</button>
            </div>
        </form>
    <?php endif; ?>
<?php endforeach; ?>
</body>
</html>
