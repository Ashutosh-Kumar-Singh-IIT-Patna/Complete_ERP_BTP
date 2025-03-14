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
$pdf->SetDrawColor(200, 200, 200); // Light grey border color

// Scholar Info Table (with borders)
$pdf->Ln(2);
foreach ([
    ['Name Of The Scholar', 'King Kumar'],
    ['Roll No', '2221cs99'],
    ['Date Of Birth', '17 July 2022'],
    ['Department', 'Computer Science And Engineering'],
    ['Nationality', 'Indian'],
    ['Scholar Category', 'Regular & Full-Time (Institute Fellow)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(90, 8, $row[1], 1, 1, 'L');
}

// Supervisor & Co-Supervisor Table
$pdf->Ln(3);
foreach ([
    ['SUPERVISOR', 'Mayank Agarwal'],
    ['Co-SUPERVISOR', 'Not Applicable (NA/NA)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(90, 8, $row[1], 1, 1, 'L');
}

// Project Details Table
$pdf->Ln(3);
foreach ([
    ['a) Project Number', '##########', 'c) Project Tenure', '##########'],
    ['b) Project Name', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(40, 8, $row[1], 1, 0, 'L');
    if (isset($row[2])) {
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell(50, 8, $row[2], 1, 0, 'L');
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell(40, 8, $row[3], 1, 1, 'L');
    } else {
        $pdf->Ln(8);
    }
}

// Sponsorship & Research Area Table
$pdf->Ln(3);
foreach ([
    ['If Sponsored', '##########'],
    ['Broad Area of Research', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(90, 8, $row[1], 1, 1, 'L');
}

// Title of Form & DC Formation Table
$pdf->Ln(3);
foreach ([
    ['Title of Form', '##########'],
    ['Date of DC Formation', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(90, 8, $row[1], 1, 1, 'L');
}

// Date Grid (Tightly Packed)
$pdf->Ln(3);
$pdf->SetFont('times', '', 10);
$dates = [
    'DCFormation', 'Enrollment', 'CompExamCom', 'CompExamRpt', 'CompExamRpt2',
    'RegSem', 'RegSem2', 'AssistEnhance', 'APS-1', 'APS-2',
    'APS-3', 'APS-4', 'APS-5', 'SynopsisSub', 'SynopsisSubRpt',
    'SynopsisSem', 'PanelExam', 'ThesisRpt', 'VivaReco', 'FinalReco'
];

$colWidth = 32;
$colHeight = 6;
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

// Signature Grid (Light Grey Borders)
$pdf->Ln(5);
$pdf->SetFont('times', '', 10);
$signatures = [
    'Chairperson', 'Supervisor', 'Co-Supervisor', 'Member 1', 'Member 2',
    'Section Head', 'Staff Representative', 'Junior Researcher', 'Academic Registrar', 'Dean'
];

foreach (array_chunk($signatures, 5) as $row) {
    foreach ($row as $sig) {
        $pdf->Cell(32, 14, '', 1, 0, 'C');
    }
    $pdf->Ln(14);
    foreach ($row as $sig) {
        $pdf->Cell(32, 8, $sig, 1, 0, 'C');
    }
    $pdf->Ln(10);
}

$pdf->Output('form.pdf', 'I');
?>
