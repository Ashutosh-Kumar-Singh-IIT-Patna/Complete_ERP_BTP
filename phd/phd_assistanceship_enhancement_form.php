<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once './../dfunctions.php';
$errors = [];

$roll = $_SESSION['roll'] ?? '1921CS12';
$data = [];
$formName = 'Enhancement of Institute Research Assistantship';
$formSubmitted = false;

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
    'proj_tenure' => 'Project Tenure (in months, if working on a Project)',
    'sponsored_agency_name' => 'Sponsored Agency (if any)',
];

if ($roll) {
    $sql = "SELECT name_of_scholar, dept, roll, nationality, gender, scholar_phd_category, mobile, email, proj_num, proj_name, proj_tenure, sponsored_agency_name, form_submit_flag_assistantship_enhancement
            FROM phd_scholar WHERE roll = ?";
    $params = ["s", $roll];
    $result = execute_query($sql, $params);

    if ($result['success'] && $result['data']) {
        $data = $result['data'][0];
        $formSubmitted = ($data['form_submit_flag_assistantship_enhancement'] == 1);
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

    $required_fields = ['dateOfRegistration', 'currentStipulatedAmount', 'enhancedStipulatedAmount'];

    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            $errors[$field] = "This field is required.";
        }
    }

    if (empty($errors)) {
        $dateOfRegistration = clean_input($_POST['dateOfRegistration']);
        $dcComment = clean_input($_POST['dcComment']);
        $currentStipulatedAmount = clean_input($_POST['currentStipulatedAmount']);
        $enhancedStipulatedAmount = clean_input($_POST['enhancedStipulatedAmount']);
        $result = clean_input($_POST['result']);

        if (!$roll) {
            $errors['session'] = "Session expired. Please log in again.";
        } else {
            $active = 1;
            $sql = "UPDATE phd_scholar SET dc_comment_in_enhancement = ?, date_of_enhancement = ?, current_stipend_amount = ?, enhanced_stipend_amount = ?, result = ?, form_submit_flag_aps_1 = ? WHERE roll = ?";
            $params = ["sssissi", $dcComment, $dateOfRegistration, $currentStipulatedAmount, $enhancedStipulatedAmount, $result, $active, $roll];

            $result = execute_query($sql, $params);

            if ($result['success']) {
                $_SESSION['success'] = 'Application for '.$formName.' submitted successfully!';
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
                echo '<label>'.$value.':</label>';
                echo '<input type="text" value="'.htmlspecialchars($data[$key] ?? '').'" disabled>';
                echo '</div>';
            }
            ?>

            <div class="form-group">
                <label for="enhancedStipulatedAmount">Enhanced Stipend Amount:</label>
                <input type="number" id="enhancedStipulatedAmount" name="enhancedStipulatedAmount" min="0" step="0.01" required>
                <span class="error"><?php echo $errors['enhancedStipulatedAmount'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="currentStipulatedAmount">Current Stipend Amount:</label>
                <input type="number" id="currentStipulatedAmount" name="currentStipulatedAmount" min="0" step="0.01" required>
                <span class="error"><?php echo $errors['currentStipulatedAmount'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="dcComment">DC Comment:</label>
                <textarea id="dcComment" name="dcComment" rows="4" cols="50" maxlength="200"></textarea>
            </div>

            <div class="form-group">
                <label for="dateOfRegistration">Date of <?php echo $formName ?>:</label>
                <input type="text" id="dateOfRegistration" name="dateOfRegistration" placeholder="DD/MM/YYYY" required>
                <span class="error"><?php echo $errors['dateOfRegistration'] ?? ''; ?></span>
            </div>

            <div class="form-group">
                <label for="result">Result:</label>
                <select id="result" name="result" class="form-control" required>
                    <option value="PASS">Pass</option>
                    <option value="FAIL">Fail</option>
                </select>
                <span class="error"><?php echo $errors['result'] ?? ''; ?></span>
            </div>

            <script>
                $(document).ready(function () {
                    $("#dateOfRegistration").datepicker({
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
