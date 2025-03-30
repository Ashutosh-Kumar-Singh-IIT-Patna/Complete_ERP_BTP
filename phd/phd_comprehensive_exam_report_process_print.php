<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './../dfunctions.php';
require_once('../course_pages/TCPDF/tcpdf.php');
session_start();
$roll = $_SESSION['roll'] ?? '1921CS12';
$data = [];
$formName = 'Comprehensive Exam Report';  //Important

class MYPDF extends TCPDF {
    public function Header() {
        if ($this->page > 1){
            return;
        }
        $image_file = 'images/iitp.png';
        $this->Image($image_file, 3, 2, 20, '', 'PNG', '', 'N', false, 300, 'L', false, false, 0, false, false, false);

        $image_file = 'images/iitp_header.png';
        $this->Image($image_file, 30, 5, 100, '', 'PNG', '', 'N', false, 300, 'R', false, false, 0, false, false, false);
    }
}

$sql = 'SELECT name_of_scholar, dept, roll, nationality, gender, scholar_phd_category, mobile, email, supervisor, co_supervisor, sponsored_agency_name, proj_num, proj_name, proj_tenure, broad_research_area, form_submit_flag_compre_attempt_1, result_of_compre_attempt_1, mode_of_compre_attempt_1,
        dc_chair, dc_internal_member, dc_external_member, dc_additional_member_1, supervisor, co_supervisor, dc_phd_coordinator,
        dc_course_work_fac_1, dc_course_work_fac_2, dc_course_work_fac_3, dc_course_work_fac_4, dc_course_work_fac_5, dc_course_work_fac_6, dc_course_work_fac_7, dc_course_work_fac_8, dc_course_work_fac_9, dc_course_work_fac_10,
        date_of_dc_formation, date_of_compre_exam_committee, date_of_compre_attempt_1, date_of_compre_attempt_2, date_of_enrollment, date_of_reg_1, date_of_reg_2, date_of_enhancement, date_of_aps_1, date_of_aps_2, date_of_aps_3, date_of_aps_4, date_of_aps_5, 
        date_of_synopsis_attempt_1, date_of_synopsis_attempt_2, date_of_panel_of_examiners, date_of_viva_voce, date_of_thesis_submission , date_of_final_recommendation
        FROM phd_scholar WHERE roll = ?'; //Important
$params = ["s", $roll];
$result = execute_query($sql, $params);
if ($result['success'] && $result['data']) {
    $data = $result['data'][0];
    $formSubmitted = ($data['form_submit_flag_compre_attempt_1'] == 1);  //Important
} else {
    die('Please submit the '.$formName.' first.');
}
if (!$formSubmitted) { 
    die($formName.' not submitted yet.');
}
$departmentName =$data['dept'] ?? '';
$rollNumber = $data['roll'] ?? '';
$name = $data['name_of_scholar'] ?? '';
$nationality = $data['nationality'] ?? '';
$gender = $data['gender'] ?? '';
$scholarCategory = $data['scholar_phd_category'] ?? '';
$mobileNumber = $data['mobile'] ?? '';
$email = $data['email'] ?? '';
$supervisor = $data['supervisor'] ?? '';
$coSupervisor = $data['co_supervisor'] ?? '';
$sponsor = $data['sponsored_agency_name'] ?? '';
$projectNumber = $data['proj_num'] ?? '';
$projectName = $data['proj_name'] ?? '';
$projectTenure = $data['proj_tenure'] ?? '';
$broadAreaOfResearch = $data['broad_research_area'] ?? '';
// $titleOfForm = $data['title_of_aps_1'] ?? '';  //Important
$dateOfSeminar = formatDate($data['date_of_compre_attempt_1'] ?? '');  //Important
$mode = $data['mode_of_compre_attempt_1'] ?? '';  //Important
$performance = $data['result_of_compre_attempt_1'] ?? '';  //Important

// $commentByDC = $data['dc_comment_in_aps_1'] ?? '';  //Important
// $performance = $data['result_of_aps_1'];  // Important
$dcChair = $data['dc_chair'] ?? '';
$dcInternalMember = $data['dc_internal_member'] ?? '';
$dcExternalMember = $data['dc_external_member'] ?? '';
$dcAdditionalMember1 = $data['dc_additional_member_1'] ?? '';
$supervisor = $data['supervisor'] ?? '';
$coSupervisor = $data['co_supervisor'] ?? '';
$dcPhdCoordinator = $data['dc_phd_coordinator'] ?? '';
$dcCourseWorkFac1 = $data['dc_course_work_fac_1'] ?? '';
$dcCourseWorkFac2 = $data['dc_course_work_fac_2'] ?? '';
$dcCourseWorkFac3 = $data['dc_course_work_fac_3'] ?? '';
$dcCourseWorkFac4 = $data['dc_course_work_fac_4'] ?? '';
$dcCourseWorkFac5 = $data['dc_course_work_fac_5'] ?? '';
$dcCourseWorkFac6 = $data['dc_course_work_fac_6'] ?? '';
$dcCourseWorkFac7 = $data['dc_course_work_fac_7'] ?? '';
$dcCourseWorkFac8 = $data['dc_course_work_fac_8'] ?? '';
$dcCourseWorkFac9 = $data['dc_course_work_fac_9'] ?? '';
$dcCourseWorkFac10 = $data['dc_course_work_fac_10'] ?? '';
 
$doctoralComitteFormation = formatDate($data['date_of_dc_formation'] ?? '');
$comprehensiveExamCommitteeProcess = formatDate($data['date_of_compre_exam_committee'] ?? '');
$comprehensiveExamReportProcess = formatDate($data['date_of_compre_attempt_1'] ?? '');
$comprehensiveExamReportProcess2 = formatDate($data['date_of_compre_attempt_2'] ?? '');
$enrollmentProcess = formatDate($data['date_of_enrollment'] ?? '');
$registrationSeminar = formatDate($data['date_of_reg_1'] ?? '');
$registrationSeminar2 = formatDate($data['date_of_reg_2'] ?? '');
$assistanceshipEnhancementForm = formatDate($data['date_of_enhancement'] ?? '');
$aps1 = formatDate($data['date_of_aps_1'] ?? '');
$aps2 = formatDate($data['date_of_aps_2'] ?? '');
$aps3 = formatDate($data['date_of_aps_3'] ?? '');
$aps4 = formatDate($data['date_of_aps_4'] ?? '');
$aps5 = formatDate($data['date_of_aps_5'] ?? '');
$synopsisSubmission = formatDate($data['date_of_synopsis_attempt_1'] ?? '');
$synopsisSubmission2 = formatDate($data['date_of_synopsis_attempt_2'] ?? '');
$panelOfExaminers = formatDate($data['date_of_panel_of_examiners'] ?? '');
$recommendationForVivaVoce = formatDate($data['date_of_viva_voce'] ?? '');
$thesisSubmissionReport = formatDate($data['date_of_thesis_submission'] ?? '');
$finalRecomendationForDegree = formatDate($data['date_of_final_recomendation'] ?? '');
$duration = calculateDuration($doctoralComitteFormation, $finalRecomendationForDegree);



    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('IITP');
    $pdf->SetTitle($formName);
    $pdf->SetSubject($formName);
    $pdf->SetKeywords('PhD, PDF, APPLICATION, FORM');
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setPrintFooter(false);
    
    
    $margin_left = 20;
    $margin_right = 6;
    $margin_top = 6;
    $margin_bottom = 5;
    $pdf->SetMargins($margin_left, $margin_top, $margin_right);
    $pdf->SetHeaderMargin($margin_top);
    $pdf->SetAutoPageBreak(TRUE, $margin_bottom);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->SetFont('times', '', 10);
    
    $pdf->AddPage();

    $pdf->setCellPaddings(1.5, 0.7, 0.8, 0.5);
    $pdf->Ln(20);
	
    $available_width = ($pdf->getPageWidth()-26);
    $html = '<b>Form Name: </b>'.$formName;
    $pdf -> MultiCell($available_width, 7,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>1.  Full Name of the Scholar : </b>'. $name;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>2.  Department Name : </b>'.$departmentName;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>3.  Roll Number : </b>'.$rollNumber;
    $pdf -> MultiCell(0.43 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>4.  Nationality : </b>'.$nationality;
    $pdf -> MultiCell(0.30 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>5.  Gender : </b>'.$gender;
    $pdf -> MultiCell(0.27 * $available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>6.  PhD Admission  Category : </b>'.$scholarCategory;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>7. Mobile No : </b>'.$mobileNumber;
    $pdf -> MultiCell($available_width * 0.50, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>8. Email : </b>'.$email;
    $pdf -> MultiCell($available_width * 0.50, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>9.  Supervisor : </b>'.$supervisor;
    $pdf -> MultiCell(0.5 * $available_width , 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>10.  Co-Supervisor : </b>'.$coSupervisor;
    $pdf -> MultiCell(0.5 * $available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>11. If Sponsored (Name of agency): </b>'.$sponsor;
    $pdf -> MultiCell($available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>12a. Project No. as per Rnd : </b>'.$projectNumber;
    $pdf -> MultiCell(0.40 * $available_width  , 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>12b. Project Tenure (X months) : </b>'.$projectTenure;
    $pdf -> MultiCell(0.60 * $available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>12c. Project Name : </b>'.$projectName;
    $pdf -> MultiCell($available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>13.  Broad Area of Research : </b>'.$broadAreaOfResearch;
    $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    // $html = '<b>14. Title of '.$formName.' : </b>'.$titleOfForm;
    // $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>14. Date of '.$formName.' : </b>'.$dateOfSeminar;
    $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>15. Mode of Exam : </b>'.$mode;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>16. Result of' . $formName . ' : </b>'.$performance;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $pdf->Ln(2);

    $html = '<b>COMPREHENSIVE EXAMINATION COMMITTEE</b>';
    $pdf -> MultiCell($available_width, 0,   $html,   0,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>S.No</b>';
    $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Name</b>';
    $pdf -> MultiCell(0.6 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Signature</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);

    $num = 1;

    if($dcChair != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $html = $dcChair;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }
    
    if($supervisor != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $html = $supervisor;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($coSupervisor != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $html = $coSupervisor;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcInternalMember != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcInternalMember;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }
        
    if($dcExternalMember != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcExternalMember;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }
    
    if($dcAdditionalMember1 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcAdditionalMember1;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcPhdCoordinator != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcPhdCoordinator;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);

    }

    if($dcCourseWorkFac1 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac1;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac2 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac2;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac3 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac3;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac4 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac4;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac5 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac5;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac6 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac6;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac7 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac7;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac8 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac8;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac9 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $html = $dcCourseWorkFac9;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   true,  true,   0,   'T', false);
    }

    if($dcCourseWorkFac10 != 'NA (NA)'){
        $html = $num++;
        $pdf -> MultiCell(0.15 * $available_width, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,  0,   'T', false);
    
        $html = $dcCourseWorkFac10;
        $pdf -> MultiCell(0.6 * $available_width,  0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  0,   'T', false);
    
        $pdf -> MultiCell(0.25 * $available_width, 0,      '',   1,   'L',   false,   1,    '',    '',  true,  1,   0,   'T', false);
    }

    $html = '<b>Important Dates : </b>';
    $pdf -> MultiCell($available_width, 0,   $html,   0,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    $pdf->SetFont('times', '', 7.5);
    $html = '<b>1. Doctoral Committee Formation</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>2. Compre. Exam Committee</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>3. Compre. Exam Report</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>3a. Compre. Exam Report (A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = $doctoralComitteFormation;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamCommitteeProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamReportProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamReportProcess2;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>4. Enrollment Process</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>5. Registration Seminar</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>5a. Registration Seminar (A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>6. Assistanceship Enhancement</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $enrollmentProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $registrationSeminar;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $registrationSeminar2;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $assistanceshipEnhancementForm;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>7. APS-1</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>8. APS-2</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>9. APS-3</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>10. APS-4 </b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>11. APS-5 </b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $aps1;
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $aps2;
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $aps3;
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $aps4;
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $aps5;
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>12. Synopsis Submission</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>12a. Synopsis Submission (A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>13. Panel of Examiners</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>14. Thesis Submission Report</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $synopsisSubmission;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $synopsisSubmission2;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $panelOfExaminers;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $thesisSubmissionReport;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>15. Recommendation for Viva Voce</b>';
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>16. Final Recommendation for Degree </b>';
    $pdf -> MultiCell(0.34 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Total Duration </b>';
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $recommendationForVivaVoce;
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $finalRecomendationForDegree;
    $pdf -> MultiCell(0.34 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $duration;
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $pdf->SetFont('times', '', 9);
    $html='<b>Signature with Dates </b>';
    $pdf->MultiCell($available_width, 0, $html, 0, 'L', false, 1, '', '', true, 0, true, true, 0, 'T', false);

    $x = $pdf->GetX() - 4; // Shift all content 4mm left
    $y = $pdf->GetY() + 10;
    $col_width = 0.20 * $available_width;
    $line_height = 10;

    $labels = [
        'Member and Chairperson',
        'Supervisor',
        'Co-Supervisor',
        'Internal DC Member<br>(within Dept)',
        'External DC Member<br>(other Dept)',
    ];

    foreach ($labels as $index => $label) {
        $xPos = $x + ($index * $col_width);
        
        // Draw static line for signature
        $pdf->Line($xPos, $y, $xPos + $col_width - 5, $y);
        
        // Move below the line and add label with bold text
        $pdf->writeHTMLCell($col_width, $line_height, $xPos-3, $y + 2, '<b>' . $label . '</b>', 0, 0, false, true, 'C', true);
    }

    // Move to next line for additional members
    $y += $line_height + 10;
    $pdf->SetXY($x, $y);

    $additional_labels = [
        'Additional Member',
        'JA-Acad',
        'AR-Acad',
        'A/Dean-Academic',
        'Yes/No<br>Approved'
    ];

    foreach ($additional_labels as $index => $label) {
        $xPos = $x + ($index * $col_width);
        
        // Draw static line for signature
        $pdf->Line($xPos, $y, $xPos + $col_width - 5, $y);
        
        // Move below the line and add label with bold text
        $pdf->writeHTMLCell($col_width, $line_height, $xPos-3, $y + 2, '<b>' . $label . '</b>', 0, 0, false, true, 'C', true);
    }

    $pdf->Output($roll.'_'.$formName.'.pdf', 'I');
    if (ob_get_length()) {
        ob_clean();
    }