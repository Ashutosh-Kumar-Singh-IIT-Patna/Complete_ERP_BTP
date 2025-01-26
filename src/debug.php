<?php
// Enable error reporting for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include the functions.php file (dbConnect should be defined there)
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
    $roll = $_SESSION['roll'] ?? null;
    $sem = $_SESSION['sem_no'] ?? null;

    if ($roll && $sem) {
        // Your SQL query
        $sql_registered = "
            SELECT cr.course_code, cm.course_name, cm.`l-t-p` AS ltp, cm.c AS credits, cr.type
            FROM course_reg_table cr
            JOIN course_master cm ON cr.course_code = cm.course_code
            WHERE cr.roll = ? AND cr.sem = ? AND (cr.type LIKE 'HS%' OR cr.type LIKE 'IDE%' OR cr.type LIKE 'DE%')
        ";

        // Pass parameters directly, without the wrapper function
        $params = [$roll, $sem];
        $types = 'si';
        $registered_courses = executeQuery($sql_registered, $params, $types, 'select');


            // Check if results are returned
            if ($registered_courses) {
                echo "<pre>" . print_r($registered_courses, true) . "</pre>";
            } else {
                echo "No registered courses found.<br>";
            }

    } else {
        echo "Session variables 'roll' and 'sem_no' are not set.<br>";
    }
?>
<?php foreach ($registered_courses as $index => $course): ?>
    <?php
        $type = $course['type'];
        echo "<pre>Course Type: $type</pre>";
    ?>
    <?php if (in_array(substr($type, 0, 2), ['HS', 'DE', 'IDE'])): ?>
    <div>Test Content</div>  <!-- Just to check rendering -->\
        <form method="post">
            <!-- Your form content here -->
        </form>
    <?php endif; ?>
<?php endforeach; ?>

<?php if (empty($registered_courses)): ?>
    <p>No registered courses found.</p>
<?php else: ?>
    <?php foreach ($registered_courses as $index => $course): ?>
        <?php $type = $course['type']; ?>
        <?php if (in_array(substr($type, 0, 2), ['HS', 'DE', 'IDE'])): ?>
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
                </div>
            </form>
        <?php endif; ?>
    <?php endforeach; ?>
<?php endif; ?>


<?php
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
            AND cem.sem = ?
            AND cem.floated = 1
            AND LEFT(cem.roll, 4) = LEFT(?, 4)
            AND LEFT(cem.roll, 6) != LEFT(?, 6)";

        $params = [$type, $_SESSION['sem_no'], $roll, $roll];
        $types = 'siss';
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
