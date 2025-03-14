<?php
require_once('../course_pages/TCPDF/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('times', 'B', 18);
        $this->Cell(0, 10, 'IIT Patna', 0, 1, 'C');
        $this->SetFont('times', '', 13);
        $this->Cell(0, 10, 'Form Name', 0, 1, 'C');
        $this->Ln(3);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(10, 15, 10);
$pdf->AddPage();
$pdf->SetFont('times', '', 11.5);

$fields = [
    ['Name Of The Scholar', 'King Kumar'],
    ['Roll No', '2221cs99'],
    ['Date Of Birth', '17 July 2022'],
    ['Department', 'Computer Science And Engineering'],
    ['Nationality', 'Indian'],
    ['Scholar Category', 'Regular & Full-Time (Institute Fellow)']
];

foreach ($fields as $index => $row) {
    if ($index % 2 == 0) $pdf->Ln(1);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(45, 10, $row[0], 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(45, 10, $row[1], 0, ($index % 2 == 1) ? 1 : 0);
}

$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'SUPERVISOR', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, 'Mayank Agarwal', 0, 0);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'Co-SUPERVISOR', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, 'Not Applicable (NA/NA)', 0, 1);

$pdf->Ln(3);
$project = [
    ['a) Project Number', '##########', 'c) Project Tenure', '##########'],
    ['b) Project Name', '##########']
];
foreach ($project as $index => $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(45, 10, $row[0], 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(45, 10, $row[1], 0, 0);
    if (isset($row[2])) {
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(45, 10, $row[2], 0, 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(45, 10, $row[3], 0, 1);
    } else {
        $pdf->Ln(10);
    }
}

$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'If Sponsored', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, '##########', 0, 0);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'Broad Area of Research', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, '##########', 0, 1);

$pdf->Ln(3);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'Title of Form', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, '##########', 0, 0);
$pdf->SetTextColor(0, 0, 255);
$pdf->Cell(45, 10, 'Date of DC Formation', 0, 0);
$pdf->SetTextColor(0, 0, 0);
$pdf->Cell(45, 10, '##########', 0, 1);

$pdf->Ln(3);
$pdf->SetFont('times', '', 10);
$dates = [
    'DCFormation', 'Enrollment', 'CompExamCom', 'CompExamRpt', 'CompExamRpt2',
    'RegSem', 'RegSem2', 'AssistEnhance', 'APS-1', 'APS-2',
    'APS-3', 'APS-4', 'APS-5', 'SynopsisSub', 'SynopsisSubRpt',
    'SynopsisSem', 'PanelExam', 'ThesisRpt', 'VivaReco', 'FinalReco'
];

$colWidth = 35;
$colHeight = 7;
$colCount = 5;
foreach (array_chunk($dates, $colCount) as $row) {
    foreach ($row as $date) {
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell($colWidth, $colHeight, $date, 1, 0, 'C');
    }
    $pdf->Ln($colHeight);
    foreach ($row as $date) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($colWidth, $colHeight, 'DD-MM-YYYY', 1, 0, 'C');
    }
    $pdf->Ln($colHeight);
}

$pdf->Ln(5);
$pdf->SetFont('times', '', 10);
$signatures = [
    'Chairperson', 'Supervisor', 'Co-Supervisor', 'Member 1', 'Member 2',
    'Section Head', 'Staff Representative', 'Junior Researcher', 'Academic Registrar', 'Dean'
];

$pdf->SetDrawColor(200, 200, 200);
foreach (array_chunk($signatures, 5) as $row) {
    foreach ($row as $sig) {
        $pdf->Cell(40, 12, '', 1, 0, 'C');
    }
    $pdf->Ln(12);
    foreach ($row as $sig) {
        $pdf->Cell(40, 12, $sig, 1, 0, 'C');
    }
    $pdf->Ln(12);
}

$pdf->Output('form.pdf', 'I');
?>
