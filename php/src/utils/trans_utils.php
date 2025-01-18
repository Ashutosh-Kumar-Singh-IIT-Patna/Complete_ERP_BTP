<?php

function getsemgrades($rollno) {
    $sql = "SELECT sem, course_code, c, grade, `type` FROM course_global_table WHERE roll = ? ORDER BY sem";
    $params = [$rollno];
    $types = "s";
    return executeQuery($sql, $params, $types, 'select');
}

function getSubjectDetails($course_code) {
    // SQL query to fetch subject name and ltp
    $sql = "SELECT course_name, `l-t-p` FROM course_master WHERE course_code = ?";
    $params = [$course_code];

    // Use executeQuery function to get subject details
    $subjectDetails = executeQuery($sql, $params, 's', 'select');

    // Return the first result if available
    return isset($subjectDetails[0]) ? $subjectDetails[0] : null;
}


function calculateSPI($grades, $credits, $gradePoints) {
    $totalPoints = 0;
    $totalCredits = 0;

    foreach ($grades as $index => $grade) {
        $totalPoints += $gradePoints[$grade] * $credits[$index];
        $totalCredits += $credits[$index];
    }

    return $totalCredits ? $totalPoints / $totalCredits : 0;
}

function calculateCPI($allGrades, $allCredits, $gradePoints) {
    $totalPoints = 0;
    $totalCredits = 0;

    foreach ($allGrades as $semester => $grades) {
        foreach ($grades as $index => $grade) {
            $totalPoints += $gradePoints[$grade] * $allCredits[$semester][$index];
            $totalCredits += $allCredits[$semester][$index];
        }
    }

    return $totalCredits ? $totalPoints / $totalCredits : 0;
}

function processStudentData($studentData, $gradePoints) {
    $result = [];
    $allGrades = [];
    $allCredits = [];

    foreach ($studentData as $data) {
        $semno = $data['sem'];

        if (!isset($result[$semno])) {
            $result[$semno] = [
                'credits_taken' => 0,
                'credits_cleared' => 0,
                'spi' => 0,
                'cpi' => 0
            ];
        }

        if (!isset($allGrades[$semno])) {
            $allGrades[$semno] = [];
            $allCredits[$semno] = [];
        }

        $grade = $data['grade'];
        $credits = $data['c'];

        $result[$semno]['credits_taken'] += $credits;
        if ($grade != 'F') {
            $result[$semno]['credits_cleared'] += $credits;
        }

        $allGrades[$semno][] = $grade;
        $allCredits[$semno][] = $credits;
    }

    $cumulativeGrades = [];
    $cumulativeCredits = [];

    foreach ($result as $semno => &$semResult) {
        $semResult['spi'] = calculateSPI($allGrades[$semno], $allCredits[$semno], $gradePoints);

        $cumulativeGrades = array_merge($cumulativeGrades, $allGrades[$semno]);
        $cumulativeCredits = array_merge($cumulativeCredits, $allCredits[$semno]);

        $semResult['cpi'] = calculateCPI([$cumulativeGrades], [$cumulativeCredits], $gradePoints);
    }

    return $result;
}
