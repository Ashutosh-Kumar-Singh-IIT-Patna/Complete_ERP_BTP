<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once './../dfunctions.php';
$errors = [];

$roll = $_SESSION['roll'] ?? '1921CS12'; 
$data = [];
$formName = 'Comprehensive Exam Committee';
$formSubmitted = false; // Flag to check if the form is already submitted

$mp = [
    'name_of_scholar' => 'Full Name of the Scholar',
    'dept' => 'Department Name',
    'roll' => 'Roll Number',
    'nationality' => 'Nationality',
    'gender' => 'Gender',
    'scholar_phd_category' => 'PhD Admission Category',
    'mobile' => 'Mobile No',
    'email' => 'Email',
    'proj_num' => 'Project Number as per RnD (If working on a Project)',
    'proj_name' => 'Project Name (If working on a Project)',
    'proj_tenure' => 'Project Tenure(X months) (If working on a Project)',
    'sponsored_agency_name' => 'Sponsored Agency (if any)',
    'dc_chair' => 'Member and Chairman',
    'supervisor' => 'Supervisor',
    'co_supervisor' => 'Co-Supervisor',
    'dc_internal_member' => 'DC Internal Member',
    'dc_external_member' => 'DC External Member',
    'dc_additional_member_1' => 'DC Additional Member 1',
];

$course = [
    'dc_course_work_fac_1' => 'Course Work Faculty 1',
    'dc_course_work_fac_2' => 'Course Work Faculty 2',
    'dc_course_work_fac_3' => 'Course Work Faculty 3',
    'dc_course_work_fac_4' => 'Course Work Faculty 4',
    'dc_course_work_fac_5' => 'Course Work Faculty 5',
    'dc_course_work_fac_6' => 'Course Work Faculty 6',
    'dc_course_work_fac_7' => 'Course Work Faculty 7',
    'dc_course_work_fac_8' => 'Course Work Faculty 8',
    'dc_course_work_fac_9' => 'Course Work Faculty 9',
    'dc_course_work_fac_10' => 'Course Work Faculty 10',
];

if ($roll) {
    // Fetch scholar details along with form_submit_flag_thesis
    $sql = "SELECT name_of_scholar, dept, roll, nationality, gender, category, scholar_phd_category, mobile, email, proj_num, proj_name, proj_tenure, sponsored_agency_name, 
            form_submit_flag_compre_exam_committee, dc_chair, supervisor, co_supervisor, dc_internal_member, dc_external_member, dc_additional_member_1
            FROM phd_scholar WHERE roll = ?";
    $params = ["s", $roll];
    $result = execute_query($sql, $params);

    if ($result['success'] && $result['data']) {
        $data = $result['data'][0];
        $formSubmitted = ($data['form_submit_flag_compre_exam_committee'] == 1); // Check if form was already submitted
    } else {
        $errors['fetch'] = "Failed to fetch user details.";
    }
} else {
    $errors['session'] = "Session expired. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $updateData = [];
    if (!empty($_POST['date_of_compre_exam_committee'])) {
        $updateData['date_of_compre_exam_committee'] = clean_input($_POST['date_of_compre_exam_committee']);
    }

    foreach ($course as $key => $label) {
        if (!empty($_POST[$key])) {
            $updateData[$key] = clean_input($_POST[$key]);
        }
    }

    if (!empty($updateData)) {
        $updateData['form_submit_flag_compre_exam_committee'] = 1;

        $updateCols = implode(' = ?, ', array_keys($updateData)) . ' = ?';
        $params = array_values($updateData);
        $params[] = $roll; // Add roll number to WHERE clause

        $sql = "UPDATE phd_scholar SET $updateCols WHERE roll = ?";
        $result = execute_query($sql, array_merge([str_repeat('s', count($updateData)) . 's'], $params));

        if ($result['success']) {
            $_SESSION['success'] = $formName . ' details updated successfully!';
            header("Location: buffer.php");
            exit();
        } else {
            $errors['db'] = "Database error: " . $result['error'];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $formName ?></title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="card shadow p-4">
            <h2 class="text-center mb-4"> <?php echo $formName ?> </h2>
            <?php if (!empty($_SESSION['success'])): ?>
                <div class="alert alert-success"> <?php echo $_SESSION['success']; unset($_SESSION['success']); ?> </div>
            <?php endif; ?>
            
            <?php if ($formSubmitted): ?>
                <div class="alert alert-info text-center">You have already submitted this form.</div>
            <?php else: ?>
                <form action="" method="POST">
                    <div class="row">
                        <?php foreach ($mp as $key => $value): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"> <?php echo $value; ?> </label>
                                <input type="text" name="<?php echo $key; ?>" class="form-control" value="<?php echo htmlspecialchars($data[$key] ?? ''); ?>" disabled readonly>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold" for="date_of_compre_exam_committee">Date of <?php echo $formName ?>:</label>
                        <input type="text" id="date_of_compre_exam_committee" name="date_of_compre_exam_committee" class="form-control" placeholder="YYYY-MM-DD" required>
                        <span class="error"><?php echo $errors['date_of_compre_exam_committee'] ?? ''; ?></span>
                    </div>

                    <div class="row">
                        <?php foreach ($course as $key => $value): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"> <?php echo $value; ?> </label>
                                <select name="<?php echo $key; ?>" class="form-select" required>
                                    <option value="NA (NA)">NA (NA)</option>
                                    <option value="Course Work (PhD)">Course Work (PhD)</option>
                                </select>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $("#date_of_compre_exam_committee").datepicker({
                                dateFormat: "yy-mm-dd", // Change to SQL-friendly format
                                changeMonth: true,
                                changeYear: true,
                                yearRange: "2000:2030"
                            });
                        });
                    </script>

                    <button type="submit">Submit</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 
