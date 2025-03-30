<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();

include('../course_pages/TCPDF/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        if ($this->page > 1) {
            return;
        }
        $image_file1 = __DIR__ . '/images/iitp.png';
        $image_file2 = __DIR__ . '/images/iitp_header.png';

        if (file_exists($image_file1)) {
            $this->Image($image_file1, 3, 2, 20, '', 'PNG', '', 'N', false, 300, 'L', false, false, 0, false, false, false);
        }
        if (file_exists($image_file2)) {
            $this->Image($image_file2, 30, 5, 100, '', 'PNG', '', 'N', false, 300, 'R', false, false, 0, false, false, false);
        }
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('IITP');
$pdf->SetTitle('PhD APPLICATION FORM');
$pdf->SetSubject('PhD APPLICATION FORM');
$pdf->SetKeywords('PhD, PDF, APPLICATION, FORM');

$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->setPrintFooter(false);

$pdf->SetMargins(20, 10, 10);
$pdf->SetHeaderMargin(10);
$pdf->SetAutoPageBreak(TRUE, 10);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->SetFont('times', '', 11);

$pdf->AddPage();
$pdf->Ln(10);

$pdf->Cell(0, 10, 'Fill in the details below:', 0, 1, 'L');
$pdf->Ln(5);

$formFields = [
    'Full Name of the Scholar' => 'name',
    'Department Name' => 'departmentName',
    'Roll Number' => 'rollNumber',
    'Nationality' => 'nationality',
    'Category' => 'scholarCategory',
    'Mobile No' => 'mobileNo',
    'Email' => 'email',
    'Supervisor' => 'supervisor',
    'Co-Supervisor' => 'coSupervisor',
    'Sponsored Agency (if any)' => 'sponsor',
    'Project Number' => 'projectNumber',
    'Project Tenure' => 'projectTenure',
    'Project Name' => 'projectName',
    'Broad Area of Research' => 'broadAreaOfResearch',
    'Title of Form' => 'titleOfForm',
    'Signature' => 'signature'
];

foreach ($formFields as $label => $fieldName) {
    $pdf->Cell(60, 8, $label . ':', 0, 0, 'L');
    $pdf->TextField($fieldName, 80, 8);
    $pdf->Ln(10);
}

// Gender Dropdown
$pdf->Cell(60, 8, 'Gender:', 0, 0, 'L');
$pdf->ComboBox('gender', 40, 8, ['Male' => 'Male', 'Female' => 'Female', 'Other' => 'Other']);
$pdf->Ln(15);

// Date of Seminar (Editable text field for date)
$pdf->Cell(60, 8, 'Date of Seminar:', 0, 0, 'L');
$pdf->TextField('dateOfSeminar', 60, 8, ['default' => 'YYYY-MM-DD']);
$pdf->Ln(15);

// Performance of Candidate Dropdown (SATISFACTORY / Non Satisfactory)
$pdf->Cell(60, 8, 'Performance of the Candidate:', 0, 0, 'L');
$pdf->ComboBox('performance', 60, 8, ['SATISFACTORY' => 'SATISFACTORY', 'NON SATISFACTORY' => 'NON SATISFACTORY']);
$pdf->Ln(15);

// Comment by DC (Text Area with 300-character limit)
$pdf->Cell(60, 8, 'Comment by DC:', 0, 0, 'L');
$pdf->TextField('commentByDC', 120, 30, ['multiline' => true, 'maxlen' => 300]);
$pdf->Ln(35);

// Submit Button
$pdf->Cell(60, 10, 'Submit Form:', 0, 0, 'L');
$pdf->Button('submit', 30, 10, 'Submit', ['action' => 'SubmitForm', 'url' => 'process.php']);
$pdf->Ln(20);

// Output PDF
$pdf->Output('fillable_phd_application.pdf', 'I');

?>
