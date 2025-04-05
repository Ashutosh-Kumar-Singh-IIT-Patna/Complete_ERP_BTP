<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

$rollno = $_SESSION['roll'];
$semNo = $_SESSION['sem_no'];

require_once 'TCPDF/tcpdf.php';
require_once 'utils/trans_utils.php';

class MYPDF extends TCPDF {
    public function Header() {
        if ($this->page > 1) return;
        $image_file = 'images/iitp.png';
        $this->Image($image_file, 3, 2, 20, '', 'PNG', '', 'N', false, 300, 'L', false, false, 0, false, false, false);

        $image_file = 'images/iitp_header.png';
        $this->Image($image_file, 30, 5, 100, '', 'PNG', '', 'N', false, 300, 'R', false, false, 0, false, false, false);
    }
}

ob_start(); // Start output buffering

$gradePoints = [
    'AU' => 10, 'PP' => 10, 'AA' => 10, 'AB' => 9, 'BB' => 8,
    'BC' => 7, 'CC' => 6, 'CD' => 5, 'DD' => 4, 'F' => 0,
    'I' => 0, 'NP' => 0, 'NU' => 0, 'X' => 0
];

$studentData = getsemgrades($rollno);
$processedData = processStudentData($studentData, $gradePoints);

// Filter only current semester data
$currentSemesterData = array_filter($studentData, function($subject) use ($semNo) {
    return $subject['sem'] == $semNo;
});

$name = getStudentName($rollno); // Replace with dynamic name if available
$yearOfAdmission = '20' . substr($rollno, 0, 2);
$programme = [
    '0' => 'B.Tech',
    '1' => 'M.Tech',
    '2' => 'Ph.D'
][substr($rollno, 2, 1)] ?? 'Unknown';

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
$pdf->SetFont('times', '', 10.5);
$pdf->AddPage();

$pdf->setCellPaddings(1.5, 0.7, 0.8, 0.5);
$pdf->Ln(20);

$available_width = ($pdf->getPageWidth() - 26);

$pdf->MultiCell($available_width, 7, '<b>Name: </b>' . $name, 1, 'L', false, 1);
$pdf->MultiCell(0.5 * $available_width, 0, '<b>Roll Number: </b>' . $rollno, 1, 'L', false, 0);
$pdf->MultiCell(0.25 * $available_width, 0, '<b>Year of Admission: </b>' . $yearOfAdmission, 1, 'L', false, 0);
$pdf->MultiCell(0.25 * $available_width, 0, '<b>Semester No: </b>' . $semNo, 1, 'L', false, 1);
$pdf->MultiCell(0.3 * $available_width, 0, '<b>Programme: </b>' . $programme, 1, 'L', false, 1);
$pdf->Ln(15);

// Generate only current semester table
$htmlSemester = generateSemesterTable($currentSemesterData, $semNo);
$pdf->writeHTML($htmlSemester, true, false, true, false, '');

if (ob_get_length()) ob_clean();
$pdf->Output('PhD_Application_Form.pdf', 'I');
?>
