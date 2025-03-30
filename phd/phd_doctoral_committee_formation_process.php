<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require_once './../dfunctions.php';
$errors = [];

$roll = $_SESSION['roll'] ?? '1921CS12'; 
$data = [];
$formName = 'Doctoral Committee Formation';
$formSubmitted = false;

if (!$roll) {
    $_SESSION['error'] = "Session expired. Please log in again.";
    header("Location: buffer.php");
    exit();
}

$sql_faculty = "SELECT emp_id, CONCAT(full_name, ' (', department, ')') AS display_name FROM acad_faculties";
$result_faculty = execute_query($sql_faculty);
$facultyOptions = $result_faculty['success'] ? $result_faculty['data'] : [];

usort($facultyOptions, function($a, $b) {
    return strcmp($a['display_name'], $b['display_name']);
});

$mp = [
    'name_of_scholar' => 'Full Name of the Scholar',
    'dept' => 'Department Name',
    'roll' => 'Roll Number',
    'nationality' => 'Nationality',
    'gender' => 'Gender',
    'scholar_phd_category' => 'PhD Admission Category',
    'mobile' => 'Mobile No',
    'email' => 'Email',
    'proj_num' => 'Project Number as per RnD',
    'proj_name' => 'Project Name',
    'proj_tenure' => 'Project Tenure (X months)',
    'sponsored_agency_name' => 'Sponsored Agency (if any)',
];

$facultyRoles = [
    "dc_chair" => "Member and Chairman",
    "supervisor" => "Supervisor",
    "co_supervisor" => "Co-Supervisor",
    "dc_internal_member" => "DC Internal Member",
    "dc_external_member" => "DC External Member",
    "dc_additional_member_1" => "DC Additional Member 1"
];

$sql = "SELECT * FROM phd_scholar WHERE roll = ?";
$params = ["s", $roll];
$result = execute_query($sql, $params);

if ($result['success'] && $result['data']) {
    $data = $result['data'][0];
    $formSubmitted = ($data['form_submit_flag_dc'] == 1);
} else {
    $errors['fetch'] = "Failed to fetch user details.";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function clean_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $updateData = [];

    // Update fields from $mp
    foreach ($mp as $key => $label) {
        if (isset($_POST[$key])) {
            $updateData[$key] = clean_input($_POST[$key]);
        }
    }

    // Update Date of DC Formation
    if (!empty($_POST['date_of_dc_formation'])) {
        $updateData['date_of_dc_formation'] = clean_input($_POST['date_of_dc_formation']);
    }

    // Update Faculty Roles (both emp_id and display_name)
    foreach ($facultyRoles as $key => $label) {
        if (!empty($_POST[$key])) {
            $updateData[$key] = clean_input($_POST[$key]);  // Store display_name
            $updateData[$key . '_emp_id'] = clean_input($_POST[$key . '_id']);  // Store emp_id
        }
    }    

    // Check if there is anything to update
    if (!empty($updateData)) {
        $updateData['form_submit_flag_dc'] = 1; // Mark form as submitted

        // Prepare SQL query dynamically
        $updateCols = implode(' = ?, ', array_keys($updateData)) . ' = ?';
        $params = array_values($updateData);
        $params[] = $roll; // Add roll number to WHERE clause

        $sql = "UPDATE phd_scholar SET $updateCols WHERE roll = ?";
        $result = execute_query($sql, array_merge([str_repeat('s', count($updateData)) . 's'], $params));

        if ($result['success']) {
            $_SESSION['success'] = 'Doctoral Committee details updated successfully!';
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
                                <input type="text" name="<?php echo $key; ?>" class="form-control" value="<?php echo htmlspecialchars($data[$key] ?? ''); ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Date of DC Formation</label>
                        <input type="text" id="date_of_dc_formation" name="date_of_dc_formation" class="form-control" value="<?php echo htmlspecialchars($data['date_of_dc_formation'] ?? ''); ?>" required>
                    </div>
                    
                    <div class="row">
                        <?php foreach ($facultyRoles as $key => $label): ?>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold"> <?php echo $label; ?> </label>
                                <select name="<?php echo $key; ?>" class="form-control" onchange="updateHiddenInput(this, '<?php echo $key; ?>_id');">
                                    <?php if ($key === 'dc_chair'): ?>
                                        <option value="HoD (dept)" data-id="3" selected>HoD (dept)</option>
                                    <?php else: ?>
                                        <?php foreach ($facultyOptions as $faculty): ?>
                                            <option value="<?php echo $faculty['display_name']; ?>" data-id="<?php echo $faculty['emp_id']; ?>" <?php echo ($data[$key] ?? '') === $faculty['display_name'] ? 'selected' : ''; ?>>
                                                <?php echo $faculty['display_name']; ?>
                                            </option>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                                <input type="hidden" name="<?php echo $key; ?>_id" id="<?php echo $key; ?>_id" value="<?php echo $data[$key . '_emp_id'] ?? ''; ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            <?php endif; ?>
        </div>
    </div>

    <script>
    $(document).ready(function () {
        $("#date_of_dc_formation").datepicker({
            dateFormat: "yy-mm-dd", // Change to SQL-friendly format
            changeMonth: true,
            changeYear: true,
            yearRange: "2000:2030"
        });
    });
    function updateHiddenInput(selectElement, hiddenInputId) {
        var selectedOption = selectElement.options[selectElement.selectedIndex];
        var empId = selectedOption.getAttribute('data-id');
        document.getElementById(hiddenInputId).value = empId;
        console.log("Updated " + hiddenInputId + " with emp_id: " + empId);
    }
    </script>
</body>
</html>
