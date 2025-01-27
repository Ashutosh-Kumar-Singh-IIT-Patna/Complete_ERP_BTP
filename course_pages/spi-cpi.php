<?php

require_once('functions.php');
session_start();

// Simulate receiving roll number (in a real implementation, this would be passed from the form)
$roll_no = $_SESSION['roll'];

// Mock name for now (can fetch from name_roll as before)
$name = "John Doe";

// Determine the course from the roll number
$courseMap = [
    'CS' => 'Computer Science and Engineering',
    'CB' => 'Chemical and Biochemical Engineering',
    'CE' => 'Civil Engineering',
    'EE' => 'Electrical Engineering',
    'ME' => 'Mechanical Engineering',
    'MM' => 'Metallurgical and Materials Engineering',
    'AI' => 'Artificial Intelligence and Data Science',
    'MC' => 'Mathematics and Computing',
    'EP' => 'Engineering Physics',
    'CH' => 'Chemical Science and Technology',
];

$courseCode = substr($roll_no, 4, 2);
$course = isset($courseMap[$courseCode]) ? $courseMap[$courseCode] : 'Unknown Course';

// Get Student Data from the new `course_glob_table`
$query = "SELECT * FROM course_global_table WHERE roll = ? ORDER BY sem";
$params = [$roll_no];
$data = executeQuery($query, $params, 's', 'select');

// Fetch course details from `course_master`
function getSubjectDetails($courseCode) {
    $query = "SELECT * FROM course_master WHERE course_code = ?";
    $params = [$courseCode];
    $result = executeQuery($query, $params, 's', 'select');
    return [
        'course_name' => $result[0]['course_name'],
        'ltp' => $result[0]['l-t-p'],
        'credits' => $result[0]['c'],
        'type' => $result[0]['type']
    ];
}

function calculateMetrics($data, $gradePoints) {
    $semesters = [];
    $totalCreditsTaken = 0;
    $totalCreditsCleared = 0;
    $totalWeightedGradePoints = 0;
    $coursesBySemester = [];

    foreach ($data as $record) {
        $semno = $record['sem'];
        $courseCode = $record['course_code'];
        $grade = $record['grade'];
        $credits = $record['c'];
        $courseType = $record['type'];

        // Initialize the semester
        if (!isset($semesters[$semno])) {
            $semesters[$semno] = [
                'creditsTaken' => 0,
                'creditsCleared' => 0,
                'weightedGradePoints' => 0,
                'totalCreditsTaken' => 0,
                'totalCreditsCleared' => 0,
                'SPI' => 0,
                'CPI' => 0,
            ];
            $coursesBySemester[$semno] = [];
        }

        // Fetch subject details
        $subjectDetails = getSubjectDetails($courseCode);

        // Store course details for the semester
        $coursesBySemester[$semno][] = [
            'course_code' => $courseCode,
            'course_name' => $subjectDetails['course_name'],
            'ltp' => $subjectDetails['ltp'],
            'credits' => $credits,
            'course_type' => $courseType,
            'grade' => $grade,
        ];

        // Calculate SPI for the semester
        $semesters[$semno]['creditsTaken'] += $credits;
        $semesters[$semno]['weightedGradePoints'] += $credits * $gradePoints[$grade];

        // If the course is passed, add to credits cleared
        if ($gradePoints[$grade] > 0) {
            $semesters[$semno]['creditsCleared'] += $credits;
        }
    }

    foreach ($semesters as $semno => &$semester) {
        $totalCreditsTaken += $semester['creditsTaken'];
        $totalCreditsCleared += $semester['creditsCleared'];
        $totalWeightedGradePoints += $semester['weightedGradePoints'];

        // SPI = Weighted grade points / Total credits taken
        $semester['SPI'] = $semester['creditsTaken'] ? $semester['weightedGradePoints'] / $semester['creditsTaken'] : 0;

        // Update cumulative totals and calculate CPI
        $semester['totalCreditsTaken'] = $totalCreditsTaken;
        $semester['totalCreditsCleared'] = $totalCreditsCleared;
        $semester['CPI'] = $totalCreditsTaken ? $totalWeightedGradePoints / $totalCreditsTaken : 0;
    }

    return ['semesters' => $semesters, 'coursesBySemester' => $coursesBySemester];
}

// Usage
$gradePoints = [
    'AU' => 10, 'PP' => 10, 'AA' => 10, 'AB' => 9, 'BB' => 8, 'BC' => 7,
    'CC' => 6, 'CD' => 5, 'DD' => 4, 'F' => 0, 'I' => 0, 'NP' => 0,
    'NU' => 0, 'X' => 0
];

$metrics = calculateMetrics($data, $gradePoints);
$semestersData = $metrics['semesters'];
$coursesBySemester = $metrics['coursesBySemester'];

$numSemesters = count($semestersData);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information System</title>
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
                <th></th>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <th>
                        <form action="semester_details.php" method="POST" style="display:inline;">
                            <input type="hidden" name="roll_no" value="<?= htmlspecialchars($roll_no) ?>">
                            <input type="hidden" name="name" value="<?= htmlspecialchars($name) ?>">
                            <input type="hidden" name="course" value="<?= htmlspecialchars($course) ?>">
                            <input type="hidden" name="semno" value="<?= htmlspecialchars($semno) ?>">
                            <input type="hidden" name="courses" value="<?= htmlspecialchars(json_encode($coursesBySemester[$semno])) ?>">
                            <a href="#" onclick="this.closest('form').submit(); return false;">Semester <?= $semno ?></a>
                        </form>
                    </th>
                <?php endforeach; ?>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Semester wise Credits Taken</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= $semester['creditsTaken'] ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>Semester wise Credits Cleared</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= $semester['creditsCleared'] ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>SPI</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= round($semester['SPI'], 2) ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>Total Credits Taken</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= $semester['totalCreditsTaken'] ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>Total Credits Cleared</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= $semester['totalCreditsCleared'] ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
            <tr>
                <td>CPI</td>
                <?php foreach ($semestersData as $semno => $semester) : ?>
                    <td><?= round($semester['CPI'], 2) ?? '--' ?></td>
                <?php endforeach; ?>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>