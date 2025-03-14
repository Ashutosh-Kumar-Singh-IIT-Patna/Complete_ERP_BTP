<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
 session_start();

 include('../course_pages/TCPDF/tcpdf.php');

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

function formatDate($date) {
    $dateParts = explode('/', $date);
    if (count($dateParts) === 3) {
        $day = $dateParts[0];
        $month = $dateParts[1];
        $year = $dateParts[2];

        // Create a proper date string and format it
        return date('d-M-Y', strtotime("$year-$month-$day"));
    }
    return "Invalid date format";
}


$formName = "APS 1";
    $departmentName = "Computer Science and Engineering";
    $rollNumber = "2101AI32";
    $name = "Rahul Kumar";
    $nationality = "Indian";
    $gender = "Male"; 
    $scholarCategory = "Regular and Full Time(Institute Fellow)";
    $mobileNo = "1234567890";
    $email = "abcd@gmail.com";
    $supervisor = "Dr. XYZ";
$coSupervisor = "Dr. ABC";
$sponsor = "Company Name";
$projectNumber = "12345";
$projectName = "AI Research";
$projectTenure = "2 years, 6 months";
$broadAreaOfResearch = "AI";
$titleOfForm = "APS Submission Form";
$dateOfSeminar = "12/04/2025";
$doctoralComitteFormation = "12/03/2021";
$enrollmentProcess = "12/04/2021";
$comprehensiveExamCommitteeProcess = "12/05/2021";
$comprehensiveExamReportProcess = "12/06/2021";
$comprehensiveExamCommitteeProcess2 = "12/07/2021";
$registrationSeminar = "12/08/2021";
$registrationSeminar2 = "12/09/2021";
$assistanceshipEnhancementForm = "12/10/2021";
$aps = "12/11/2021";
$aps2 = "12/12/2021";
$aps3 = "12/01/2022";
$aps4 = "12/02/2022";
$aps5 = "12/03/2022";
$synopsisSubmission = "12/04/2022";
$synopsisSubmission2 = "12/05/2022";
$panelOfExaminers = "12/06/2022";
$thesisSubmissionReport = "12/07/2022";
$recommendationForVivaVoice = "12/08/2022";
$finalRecomendationForDegree = "12/09/2022";
$duration = "2 years, 6 months";
$commentByDC = "Good";
$performance = "SATISFACTORY";

$dateOfSeminar = formatDate($dateOfSeminar);
$doctoralComitteFormation = formatDate($doctoralComitteFormation);  
$enrollmentProcess = formatDate($enrollmentProcess);
$comprehensiveExamCommitteeProcess = formatDate($comprehensiveExamCommitteeProcess);
$comprehensiveExamReportProcess = formatDate($comprehensiveExamReportProcess);
$comprehensiveExamCommitteeProcess2 = formatDate($comprehensiveExamCommitteeProcess2);
$registrationSeminar = formatDate($registrationSeminar);
$registrationSeminar2 = formatDate($registrationSeminar2);
$assistanceshipEnhancementForm = formatDate($assistanceshipEnhancementForm);
$aps = formatDate($aps);
$aps2 = formatDate($aps2);
$aps3 = formatDate($aps3);
$aps4 = formatDate($aps4);
$aps5 = formatDate($aps5);
$synopsisSubmission = formatDate($synopsisSubmission);
$synopsisSubmission2 = formatDate($synopsisSubmission2);
$panelOfExaminers = formatDate($panelOfExaminers);
$thesisSubmissionReport = formatDate($thesisSubmissionReport);
$recommendationForVivaVoice = formatDate($recommendationForVivaVoice);
$finalRecomendationForDegree = formatDate($finalRecomendationForDegree);



    $pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    
    
    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('IITP');
    $pdf->SetTitle('PhD APPLICATION FORM');
    $pdf->SetSubject('PhD APPLICATION FORM');
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

    $pdf->SetFont('times', '', 11);
    
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
    
    $pdf->SetFont('times', '', 10);
    $html = '<b>6.  Category : </b>'.$scholarCategory;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>7. Mob No : </b>'.$mobileNo;
    $pdf -> MultiCell($available_width * 0.35, 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>8. Email : </b>'.$email;
    $pdf -> MultiCell($available_width * 0.65, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>9.  Supervisor : </b>'.$supervisor;
    $pdf -> MultiCell(0.5 * $available_width , 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>10.  Co-Supervisor : </b>'.$coSupervisor;
    $pdf -> MultiCell(0.5 * $available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>11. If Sponsored (Name of agency): '.$sponsor;
    $pdf -> MultiCell($available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>12a. Proj. No. as per Rnd : </b>'.$projectNumber;
    $pdf -> MultiCell(0.40 * $available_width  , 0,   $html,   1,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>12b. Project Tenure (X years, Y months) : </b>'.$projectTenure;
    $pdf -> MultiCell(0.60 * $available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>12c. Project Name : </b>'.$projectName;
    $pdf -> MultiCell($available_width  , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>13.  Broad Area of Research : </b>'.$broadAreaOfResearch;
    $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>14. Title of '.$formName.' : </b>'.$titleOfForm;
    $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>15. Date of Seminar : </b>'.$dateOfSeminar;
    $pdf -> MultiCell($available_width , 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $pdf->Ln(10);
    $pdf->SetFont('times', '', 7.5);
    $html = '<b>1. Doctoral Comitte Formation</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>2. Enrollment Process</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>3. Comp. Exam Committee</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>4. Comp. Exam Report</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = $doctoralComitteFormation;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $enrollmentProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamCommitteeProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamReportProcess;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>4a. Compr. Exam Report (A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>5.  Registration Seminar</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>5a.  Registration Seminar(A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>6.  Assistanceship Enhancement</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $comprehensiveExamCommitteeProcess2;
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
    
    $html = $aps;
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
    
    $html = '<b>12.1 Synopsis Submission(A2)</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>14. Panel of Examiners</b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>15. Thesis Submission Report : </b>';
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $synopsisSubmission;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $synopsisSubmission2;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $panelOfExaminers;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $thesisSubmissionReport;
    $pdf -> MultiCell(0.25 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>16. Recommendation for Viva Voice</b>';
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>17. Final Recommendation for Degree : </b>';
    $pdf -> MultiCell(0.34 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Total Duration : </b>';
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $recommendationForVivaVoice;
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $finalRecomendationForDegree;
    $pdf -> MultiCell(0.34 * $available_width, 0,   $html,   1,   'C',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = $duration;
    $pdf -> MultiCell(0.33 * $available_width, 0,   $html,   1,   'C',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    $pdf->SetFont('times', '', 10);

    $html = '<b>16. Comment By DC : </b>'.$commentByDC;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>17. Performance of the Candidate : </b>'.$performance;
    $pdf -> MultiCell($available_width, 0,   $html,   1,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    $pdf->Ln(5);
    $pdf->SetFont('times', '', 9);

    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 8.5,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 8.5,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 8.5,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 8.5,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 8.5,   $html,   0,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>Member and Chairperson</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Supervisor</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Co-Supervisor</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Additional Member1</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Additional Member2</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 14,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 14,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 14,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 14,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '';
    $pdf -> MultiCell(0.20 * $available_width, 14,   $html,   0,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);

    $html = '<b>Section</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Staff</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>JR</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>AR</b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   0,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $html = '<b>Dean<b>';
    $pdf -> MultiCell(0.20 * $available_width, 0,   $html,   0,   'L',   false,   1,    '',    '',  true,  0,   true,  true,   0,   'T', false);
    
    $pdf->Output('PhD_Application_Form.pdf', 'I');

    if (ob_get_length()) {
        ob_clean();
    }
    
