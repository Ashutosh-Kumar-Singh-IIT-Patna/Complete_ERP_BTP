<?php
require_once 'config.php';
require_once 'functions.php';

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

// Example to insert and fetch data securely
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!validateCsrfToken()) {
        die('Invalid CSRF token');
    }

    $_SESSION['roll'] = $_POST['rollno'];
    $sem_no = 7; // getSemesterNo($roll); // Semester number logic
   $_SESSION['sem_no'] = $sem_no;

    header("Location: course_register2.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>Secure ERP</title>
</head>
<body>
<div class="container">
    <h1 class="my-4">Student Information</h1>
    <form method="POST">
        <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
        <div class="form-group">
            <label for="rollno">Roll No</label>
            <input type="text" class="form-control" id="rollno" name="rollno" required>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
</div>
</body>
</html>
