<?php
session_start();
require_once '../functions.php';

$csrf_token = generate_csrf_token();
$_SESSION['csrf_token'] = $csrf_token;

$gender_options = get_dropdown_options('acad_gender');
$mother_tongue_options = get_dropdown_options('acad_mother_tongue');
$profession_options = get_dropdown_options('acad_profession');
$religion_options = get_dropdown_options('acad_religion');
$state_options = get_dropdown_options('acad_state');
$blood_group_options = get_dropdown_options('acad_blood_group');
$country_code_options = get_dropdown_options('acad_country_code');
$department_options = get_dropdown_options('acad_department');
$branch_options = get_dropdown_options('acad_branch');
$specialization_options = get_dropdown_options('acad_specialization');
$nationality_options = get_dropdown_options('acad_nationality');
$category_options = get_dropdown_options('acad_category');
$hostel_name_options = get_dropdown_options('acad_hostel_name');
$hostel_block_options = get_dropdown_options('acad_hostel_block');
$course_options = get_dropdown_options('acad_course');

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <title>User Registration</title>
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            border-radius: 0.5rem;
        }
        .card-body {
            padding: 2rem;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #007bff;
            border-color: #007bff;
        }
        .btn-primary:hover {
            background-color: #0056b3;
            border-color: #004085;
        }
    </style>
</head>
<body>
<?php include 'nav.html'; ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="card-title text-center mb-4">Student Registration</h2>
                    <form method="post" action="student_register_backend.php" enctype="multipart/form-data">
                        <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token); ?>">

                        <div class="form-group mb-3">
                            <label for="roll_number" class="form-label">Roll Number</label>
                            <input type="text" class="form-control" id="roll_number" name="roll_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="full_name" class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="full_name" name="full_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="preferred_name" class="form-label">Preferred Name on ID Card</label>
                            <input type="text" class="form-control" id="preferred_name" name="preferred_name">
                        </div>

                        <div class="form-group mb-3">
                            <label for="dob" class="form-label">Date of Birth</label>
                            <input type="date" class="form-control" id="dob" name="dob" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="gender" class="form-label">Gender</label>
                            <select class="form-control" id="gender" name="gender" required>
                                <?php foreach ($gender_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mother_tongue" class="form-label">Mother Tongue</label>
                            <select class="form-control" id="mother_tongue" name="mother_tongue" required>
                                <?php foreach ($mother_tongue_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="religion" class="form-label">Religion</label>
                            <select class="form-control" id="religion" name="religion" required>
                                <?php foreach ($religion_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nationality" class="form-label">Nationality</label>
                            <select class="form-control" id="nationality" name="nationality" required>
                            <?php foreach ($nationality_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="state" class="form-label">State</label>
                            <select class="form-control" id="state" name="state" required>
                                <?php foreach ($state_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="blood_group" class="form-label">Blood Group</label>
                            <select class="form-control" id="blood_group" name="blood_group" required>
                                <?php foreach ($blood_group_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-control" id="category" name="category" required>
                                <?php foreach ($category_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="country_code" class="form-label">Country Code</label>
                            <select class="form-control" id="country_code" name="country_code" required>
                                <?php foreach ($country_code_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mobile_no" class="form-label">Mobile Number</label>
                            <input type="text" class="form-control" id="mobile_no" name="mobile_no" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="whatsapp_no" class="form-label">WhatsApp Number</label>
                            <input type="text" class="form-control" id="whatsapp_no" name="whatsapp_no">
                        </div>

                        <div class="form-group mb-3">
                            <label for="admission_month" class="form-label">Admssion month</label>
                            <select class="form-control" id="admission_month" name="admission_month" required>
                                <option value="jan">JAN</option>
                                <option value="july">JULY</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="course" class="form-label">Course</label>
                            <select class="form-control" id="course" name="course" required>
                                <?php foreach ($course_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="department" class="form-label">Department Taken Admission</label>
                            <select class="form-control" id="department" name="department" required>
                                <?php foreach ($department_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="branch" class="form-label">Branch Taken Admission</label>
                            <select class="form-control" id="branch" name="branch" required>
                                <?php foreach ($branch_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="specialization" class="form-label">Specialization Taken Admission</label>
                            <select class="form-control" id="specialization" name="specialization">
                                <?php foreach ($specialization_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hostel_name" class="form-label">Hostel Name</label>
                            <select class="form-control" id="hostel_name" name="hostel_name" required>
                                <?php foreach ($hostel_name_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hostel_block" class="form-label">Hostel Block</label>
                            <select class="form-control" id="hostel_block" name="hostel_block" required>
                                <?php foreach ($hostel_block_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hostel_room_no" class="form-label">Hostel Room Number</label>
                            <input type="number" class="form-control" id="hostel_room_no" name="hostel_room_no" min="100" max="999" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="webmail" class="form-label">Webmail (Institute Email ID)</label>
                            <input type="email" class="form-control" id="webmail" name="webmail" required pattern="[a-z0-9._%+-]+@iitp\.ac\.in$">
                        </div>

                        <div class="form-group mb-3">
                            <label for="alt_mail" class="form-label">Personal Mail/Alternate Email</label>
                            <input type="email" class="form-control" id="alt_mail" name="alt_mail">
                        </div>

                        <div class="form-group mb-3">
                            <label for="current_address" class="form-label">Current Address</label>
                            <input type="text" class="form-control" id="current_address" name="current_address">
                        </div>

                        <div class="form-group mb-3">
                            <label for="permanent_address" class="form-label">Permanent Address</label>
                            <input type="text" class="form-control" id="permanent_address" name="permanent_address">
                        </div>

                        <div class="form-group mb-3">
                            <label for="date_of_admission" class="form-label">Date of Admission</label>
                            <input type="date" class="form-control" id="date_of_admission" name="date_of_admission" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="father_name" class="form-label">Father's Full Name</label>
                            <input type="text" class="form-control" id="father_name" name="father_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mother_name" class="form-label">Mother's Full Name</label>
                            <input type="text" class="form-control" id="mother_name" name="mother_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="guardian_name" class="form-label">Guardian's Full Name</label>
                            <input type="text" class="form-control" id="guardian_name" name="guardian_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="father_phone" class="form-label">Father's Phone</label>
                            <input type="text" class="form-control" id="father_phone" name="father_phone" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mother_phone" class="form-label">Mother's Phone</label>
                            <input type="text" class="form-control" id="mother_phone" name="mother_phone" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="guardian_phone" class="form-label">Guardian's Phone</label>
                            <input type="text" class="form-control" id="guardian_phone" name="guardian_phone" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="father_profession" class="form-label">Father's Profession</label>
                            <select class="form-control" id="father_profession" name="father_profession" required>
                                <?php foreach ($profession_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="mother_profession" class="form-label">Mother's Profession</label>
                            <select class="form-control" id="mother_profession" name="mother_profession" required>
                                <?php foreach ($profession_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="guardian_profession" class="form-label">Guardian's Profession</label>
                            <select class="form-control" id="guardian_profession" name="guardian_profession" required>
                                <?php foreach ($profession_options as $option): ?>
                                    <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="father_guardian_email" class="form-label">Father/guardian Email</label>
                            <input type="email" class="form-control" id="father_guardian_email" name="father_guardian_email" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="nature_of_admission" class="form-label">Nature of Admission</label>
                            <input type="text" class="form-control" id="nature_of_admission" name="nature_of_admission" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jee_registration_number" class="form-label">JEE Registration Number</label>
                            <input type="text" class="form-control" id="jee_registration_number" name="jee_registration_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jee_course_number" class="form-label">JEE Course Number</label>
                            <input type="text" class="form-control" id="jee_course_number" name="jee_course_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="jee_roll_number" class="form-label">JEE Roll Number</label>
                            <input type="text" class="form-control" id="jee_roll_number" name="jee_roll_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="all_india_rank" class="form-label">All India Rank</label>
                            <input type="text" class="form-control" id="all_india_rank" name="all_india_rank" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="category_rank" class="form-label">Category Rank</label>
                            <input type="text" class="form-control" id="category_rank" name="category_rank" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="qualified_exam_year" class="form-label">Qualified Exam Year</label>
                            <input type="text" class="form-control" id="qualified_exam_year" name="qualified_exam_year" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="qualified_exam_name" class="form-label">Qualified Exam Name</label>
                            <input type="text" class="form-control" id="qualified_exam_name" name="qualified_exam_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="board_university_name" class="form-label">Board University Name</label>
                            <input type="text" class="form-control" id="board_university_name" name="board_university_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="percent_marks" class="form-label">Percent Marks</label>
                            <input type="text" class="form-control" id="percent_marks" name="percent_marks" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hs_education_area" class="form-label">HS Education Area</label>
                            <input type="text" class="form-control" id="hs_education_area" name="hs_education_area" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="hs_education_medium" class="form-label">HS Education Medium</label>
                            <input type="text" class="form-control" id="hs_education_medium" name="hs_education_medium" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_complete_flag" class="form-label">Course Complete</label>
                            <select class="form-control" id="course_complete_flag" name="course_complete_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="onroll_status" class="form-label">Onroll Status</label>
                            <select class="form-control" id="onroll_status" name="onroll_status" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="final_cpi" class="form-label">Final CPI</label>
                            <input type="text" class="form-control" id="final_cpi" name="final_cpi" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bank_name" class="form-label">Bank Name</label>
                            <input type="text" class="form-control" id="bank_name" name="bank_name" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bank_account_number" class="form-label">Bank Account Number</label>
                            <input type="text" class="form-control" id="bank_account_number" name="bank_account_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="bank_ifsc_code" class="form-label">Bank IFSC Code</label>
                            <input type="text" class="form-control" id="bank_ifsc_code" name="bank_ifsc_code" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="op_id" class="form-label">OP ID</label>
                            <input type="text" class="form-control" id="op_id" name="op_id" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="year_of_passout" class="form-label">Year of Passout</label>
                            <input type="text" class="form-control" id="year_of_passout" name="year_of_passout" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="punish_flag" class="form-label">Punish</label>
                            <select class="form-control" id="punish_flag" name="punish_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="roll_number_old" class="form-label">Roll Number Old</label>
                            <input type="text" class="form-control" id="roll_number_old" name="roll_number_old" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="branch_change_flag" class="form-label">Branch Change</label>
                            <select class="form-control" id="branch_change_flag" name="branch_change_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="set_flag" class="form-label">Set</label>
                            <select class="form-control" id="set_flag" name="set_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="freeze_flag" class="form-label">Freeze</label>
                            <select class="form-control" id="freeze_flag" name="freeze_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="declaration_flag" class="form-label">Declaration</label>
                            <select class="form-control" id="declaration_flag" name="declaration_flag" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="current_year" class="form-label">Current Year</label>
                            <input type="text" class="form-control" id="current_year" name="current_year" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="height" class="form-label">Height</label>
                            <input type="text" class="form-control" id="height" name="height" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="weight" class="form-label">Weight</label>
                            <input type="text" class="form-control" id="weight" name="weight" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="police_station_mailing_address" class="form-label">Police Station Mailing Address</label>
                            <input type="text" class="form-control" id="police_station_mailing_address" name="police_station_mailing_address" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="police_station_permanent_address" class="form-label">Police Station Permanent Address</label>
                            <input type="text" class="form-control" id="police_station_permanent_address" name="police_station_permanent_address" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="passport_number" class="form-label">Passport Number</label>
                            <input type="text" class="form-control" id="passport_number" name="passport_number" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="course_complete_date" class="form-label">Course Complete Date</label>
                            <input type="date" class="form-control" id="course_complete_date" name="course_complete_date" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="pwd_status" class="form-label">PWD Status</label>
                            <select class="form-control" id="pwd_status" name="pwd_status" required>
                                <option value="1">YES</option>
                                <option value="0">NO</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="annual_income" class="form-label">Annual Income of Parents (in LPA)</label>
                            <select class="form-control" id="annual_income" name="annual_income" required>
                                <option value="0-2.5">0 - 2.5</option>
                                <option value="2.5-5">2.5 - 5</option>
                                <option value="5-7.5">5 - 7.5</option>
                                <option value="7.5-10">7.5 - 10</option>
                                <option value="10+">10+</option>
                            </select>
                        </div>

                        <div class="form-group mb-3">
                            <label for="photo" class="form-label">Photo</label>
                            <input type="file" class="form-control-file" id="photo" name="photo" accept="image/jpeg" required>
                            <small class="form-text text-muted">Please upload a JPEG image. It will be saved as ROLL.jpg</small>
                        </div>

                        <button type="submit" class="btn btn-primary btn-block">Register</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script>
    // Client-side validation
    document.querySelector('form').addEventListener('submit', function(e) {
        const rollNumber = document.getElementById('roll_number');
        rollNumber.value = rollNumber.value.trim().toUpperCase();

        const fullName = document.getElementById('full_name');
        fullName.value = fullName.value.replace(/\b\w/g, l => l.toUpperCase());

        const preferredName = document.getElementById('preferred_name');
        preferredName.value = preferredName.value.replace(/\b\w/g, l => l.toUpperCase());

        const mobileNo = document.getElementById('mobile_no');
        mobileNo.value = mobileNo.value.replace(/^0+/, '');

        const whatsappNo = document.getElementById('whatsapp_no');
        whatsappNo.value = whatsappNo.value.replace(/^0+/, '');

        const webmail = document.getElementById('webmail');
        webmail.value = webmail.value.toLowerCase();

        const altMail = document.getElementById('alt_mail');
        altMail.value = altMail.value.toLowerCase();

        const fatherName = document.getElementById('father_name');
        fatherName.value = fatherName.value.replace(/\b\w/g, l => l.toUpperCase());

        const motherName = document.getElementById('mother_name');
        motherName.value = motherName.value.replace(/\b\w/g, l => l.toUpperCase());
    });
</script>
</body>
</html>