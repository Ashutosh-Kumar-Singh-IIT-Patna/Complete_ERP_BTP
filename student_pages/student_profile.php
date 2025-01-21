<?php
// student_profile.php
require_once '../functions.php';

if (!isset($_GET['roll_number'])) {
    die('Roll number is required');
}

$roll_number = sanitize_input($_GET['roll_number']);

// Fetch student details
$sql = "SELECT * FROM acad_users WHERE roll_number = ?";
$query_result = execute_query($sql, ['s', $roll_number]);

if (!$query_result['success'] || $query_result['result']->num_rows == 0) {
    die('Student not found');
}

$student = $query_result['result']->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row">
        <div class="col-lg-4">
            <div class="card">
                <img src="<?php echo htmlspecialchars($student['photo_path']); ?>" class="card-img-top" alt="Student Photo">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($student['full_name']); ?></h5>
                    <p class="card-text">
                        <strong>Roll Number:</strong> <?php echo htmlspecialchars($student['roll_number']); ?><br>
                        <strong>Preferred Name:</strong> <?php echo htmlspecialchars($student['preferred_name']); ?><br>
                        <strong>Date of Birth:</strong> <?php echo htmlspecialchars($student['dob']); ?><br>
                        <strong>Gender:</strong> <?php echo htmlspecialchars($student['gender']); ?><br>
                        <strong>Blood Group:</strong> <?php echo htmlspecialchars($student['blood_group']); ?>
                    </p>
                </div>
            </div>
        </div>
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4>Profile Details</h4>
                </div>
                <div class="card-body">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Nationality</th>
                                <td><?php echo htmlspecialchars($student['nationality']); ?></td>
                            </tr>
                            <tr>
                                <th>State</th>
                                <td><?php echo htmlspecialchars($student['state']); ?></td>
                            </tr>
                            <tr>
                                <th>Mother Tongue</th>
                                <td><?php echo htmlspecialchars($student['mother_tongue']); ?></td>
                            </tr>
                            <tr>
                                <th>Religion</th>
                                <td><?php echo htmlspecialchars($student['religion']); ?></td>
                            </tr>
                            <tr>
                                <th>Category</th>
                                <td><?php echo htmlspecialchars($student['category']); ?></td>
                            </tr>
                            <tr>
                                <th>Country Code</th>
                                <td><?php echo htmlspecialchars($student['country_code']); ?></td>
                            </tr>
                            <tr>
                                <th>Mobile No</th>
                                <td><?php echo htmlspecialchars($student['mobile_no']); ?></td>
                            </tr>
                            <tr>
                                <th>WhatsApp No</th>
                                <td><?php echo htmlspecialchars($student['whatsapp_no']); ?></td>
                            </tr>
                            <tr>
                                <th>Course</th>
                                <td><?php echo htmlspecialchars($student['course']); ?></td>
                            </tr>
                            <tr>
                                <th>Department</th>
                                <td><?php echo htmlspecialchars($student['department']); ?></td>
                            </tr>
                            <tr>
                                <th>Branch</th>
                                <td><?php echo htmlspecialchars($student['branch']); ?></td>
                            </tr>
                            <tr>
                                <th>Specialization</th>
                                <td><?php echo htmlspecialchars($student['specialization']); ?></td>
                            </tr>
                            <tr>
                                <th>Hostel Name</th>
                                <td><?php echo htmlspecialchars($student['hostel_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Hostel Block</th>
                                <td><?php echo htmlspecialchars($student['hostel_block']); ?></td>
                            </tr>
                            <tr>
                                <th>Hostel Room No</th>
                                <td><?php echo htmlspecialchars($student['hostel_room_no']); ?></td>
                            </tr>
                            <tr>
                                <th>Webmail</th>
                                <td><?php echo htmlspecialchars($student['webmail']); ?></td>
                            </tr>
                            <tr>
                                <th>Alternate Email</th>
                                <td><?php echo htmlspecialchars($student['alt_mail']); ?></td>
                            </tr>
                            <tr>
                                <th>Date of Admission</th>
                                <td><?php echo htmlspecialchars($student['date_of_admission']); ?></td>
                            </tr>
                            <tr>
                                <th>Father's Name</th>
                                <td><?php echo htmlspecialchars($student['father_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Mother's Name</th>
                                <td><?php echo htmlspecialchars($student['mother_name']); ?></td>
                            </tr>
                            <tr>
                                <th>Father's Phone</th>
                                <td><?php echo htmlspecialchars($student['father_phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Mother's Phone</th>
                                <td><?php echo htmlspecialchars($student['mother_phone']); ?></td>
                            </tr>
                            <tr>
                                <th>Father's Profession</th>
                                <td><?php echo htmlspecialchars($student['father_profession']); ?></td>
                            </tr>
                            <tr>
                                <th>Mother's Profession</th>
                                <td><?php echo htmlspecialchars($student['mother_profession']); ?></td>
                            </tr>
                            <tr>
                                <th>Annual Income</th>
                                <td><?php echo htmlspecialchars($student['annual_income']); ?></td>
                            </tr>
                            <tr>
                                <th>Created At</th>
                                <td><?php echo htmlspecialchars($student['created_at']); ?></td>
                            </tr>
                            <tr>
                                <th>Updated At</th>
                                <td><?php echo htmlspecialchars($student['updated_at']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
