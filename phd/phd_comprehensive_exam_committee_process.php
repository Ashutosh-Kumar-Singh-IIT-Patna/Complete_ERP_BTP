<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once './../dfunctions.php';
$errors = [];

$roll = $_SESSION['roll'] ?? '1921CS12'; 
$data = [];
$formName = 'Compre. Exam Committee';
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
];

if ($roll) {
    // Fetch scholar details along with form_submit_flag_thesis
    $sql = "SELECT name_of_scholar, dept, roll, nationality, gender, category, scholar_phd_category, mobile, email, proj_num, proj_name, proj_tenure, sponsored_agency_name, form_submit_flag_aps_1
            FROM phd_scholar WHERE roll = ?";
    $params = ["s", $roll];
    $result = execute_query($sql, $params);

    if ($result['success'] && $result['data']) {
        $data = $result['data'][0];
        $formSubmitted = ($data['form_submit_flag_aps_1'] == 1); // Check if form was already submitted
    } else {
        $errors['fetch'] = "Failed to fetch user details.";
    }
} else {
    $errors['session'] = "Session expired. Please log in again.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && !$formSubmitted) {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $required_fields = ['titleOfThesis', 'dateOfRegistration'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "This field is required.";
        }
    }

    if (empty($errors)) {
        $titleOfThesis = clean_input($_POST['titleOfThesis']);
        $dateOfRegistration = clean_input($_POST['dateOfRegistration']);
        $dcComment = clean_input($_POST['dcComment']);
        $result = clean_input($_POST['result']);

        if (!$roll) {
            $errors['session'] = "Session expired. Please log in again.";
        } else {
            $active = 1;
            $sql = "UPDATE phd_scholar SET title_of_aps_1 = ?, date_of_aps_1 = ?, dc_comment_in_aps_1 = ?, result_of_aps_1 = ?, form_submit_flag_aps_1 = ? WHERE roll = ?";
            $params = ["ssssis", $titleOfThesis, $dateOfRegistration, $dcComment, $result, $active, $roll];

            $result = execute_query($sql, $params);

            if ($result['success']) {
                $_SESSION['success'] = 'Application '.$formName.' submitted successfully!';
                header("Location: buffer.php");
                exit();
            } else {
                $errors['db'] = "Database error. Please try again.";
            }
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; padding: 20px; background-color: #f8f9fa; }
        .container { max-width: 600px; margin: auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); }
        .form-group { margin-bottom: 15px; }
        label { font-weight: bold; display: block; }
        input, select, textarea { width: 100%; padding: 8px; border: 1px solid #ccc; border-radius: 4px; }
        .error { color: red; font-size: 12px; }
        button { background-color: #007bff; color: white; padding: 10px; border: none; border-radius: 5px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
    </style>
</head>
<body>

<div class="container">
    <h2><?php echo $formName ?></h2>

    <?php if (!empty($_SESSION['success'])): ?>
        <p style="color: green;"><?php echo $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <?php if (!empty($errors['fetch'])): ?>
        <p class="error"><?php echo $errors['fetch']; ?></p>
    <?php endif; ?>

    <?php if ($formSubmitted): ?>
        <p style="color: green;">You have already submitted this form.</p>
    <?php else: ?>
        <form action="" method="POST">
            <?php
            foreach($mp as $key => $value) {
                echo '<div class="form-group">';
                echo '<label><b>'.$value.'</b>:</label>';
                echo '<input type="text" value="'.htmlspecialchars($data[$key] ?? '').'" disabled>';
                echo '</div>';
            }
            ?>

            <div class="form-group">
                <label for="date_of_compre_exam_committee">Date of <?php echo $formName ?>:</label>
                <input type="text" id="date_of_compre_exam_committee" name="date_of_compre_exam_committee" placeholder="DD/MM/YYYY" required>
                <span class="error"><?php echo $errors['date_of_compre_exam_committee'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="result">Result:</label>
                <select id="result" name="result" class="form-control" required>
                    <option value="PASS">Pass</option>
                    <option value="FAIL">Fail</option>
                </select>
                <span class="error"><?php echo $errors['result'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="result">Mode of Exam:</label>
                <select id="result" name="result" class="form-control" required>
                    <option value="BOTH">BOTH</option>
                    <option value="ORAL">ORAL</option>
                    <option value="WRITTEN">WRITTEN</option>
                </select>
                <span class="error"><?php echo $errors['result'] ?? ''; ?></span>
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

</body>
</html> 
