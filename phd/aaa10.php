<?php
require_once('../course_pages/TCPDF/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        // Add logo
        $this->Image(__DIR__.'/logo.png', 85, 10, 40, '', 'PNG');
        $this->Ln(20);

        // Title
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
$pdf->SetFont('times', '', 10);

// Scholar Info Table (Grouped Name & Roll No.)
$pdf->Ln(10);
$pdf->SetDrawColor(230, 230, 230); // Lightest border
foreach ([
    ['Name Of The Scholar', 'King Kumar', 'Roll No', '2221cs99'],
    ['Date Of Birth', '17 July 2022', 'Department', 'Computer Science And Engineering'],
    ['Nationality', 'Indian', 'Scholar Category', 'Regular & Full-Time (Institute Fellow)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->MultiCell(50, 8, $row[0], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(50, 8, $row[1], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->MultiCell(50, 8, $row[2], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(50, 8, $row[3], 1, 'L', 0, 1);
}

// Supervisor & Co-Supervisor Table
$pdf->Ln(3);
foreach ([
    ['SUPERVISOR', 'Mayank Agarwal', 'Co-SUPERVISOR', 'Not Applicable (NA/NA)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->MultiCell(50, 8, $row[0], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(50, 8, $row[1], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 255);
    $pdf->MultiCell(50, 8, $row[2], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(50, 8, $row[3], 1, 'L', 0, 1);
}

// Project Details Table
$pdf->Ln(3);
foreach ([
    ['a) Project Number', '##########', 'c) Project Tenure', '##########'],
    ['b) Project Name', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->MultiCell(50, 8, $row[0], 1, 'L', 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->MultiCell(50, 8, $row[1], 1, 'L', 0, 0);
    if (isset($row[2])) {
        $pdf->SetTextColor(0, 0, 255);
        $pdf->MultiCell(50, 8, $row[2], 1, 'L', 0, 0);
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell(50, 8, $row[3], 1, 'L', 0, 1);
    } else {
        $pdf->Ln(8);
    }
}

// Date Grid (50% Dark Border)
$pdf->Ln(3);
$pdf->SetFont('times', '', 10);
$pdf->SetDrawColor(120, 120, 120);
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
        $pdf->MultiCell($colWidth, $colHeight, $date, 1, 'C', 0, 0);
    }
    $pdf->Ln($colHeight);
    foreach ($row as $date) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->MultiCell($colWidth, $colHeight, 'DD-MM-YYYY', 1, 'C', 0, 0);
    }
    $pdf->Ln($colHeight);
}

// Signature Grid (25% Dark Border)
$pdf->Ln(5);
$pdf->SetFont('times', '', 10);
$pdf->SetDrawColor(180, 180, 180);
$signatures = ['Chairperson', 'Supervisor', 'Co-Supervisor', 'Member 1', 'Member 2',
    'Section Head', 'Staff Representative', 'Junior Researcher', 'Academic Registrar', 'Dean'];

foreach (array_chunk($signatures, 5) as $row) {
    foreach ($row as $sig) {
        $pdf->MultiCell(40, 14, '', 1, 'C', 0, 0);
    }
    $pdf->Ln(14);
    foreach ($row as $sig) {
        $pdf->MultiCell(40, 8, $sig, 1, 'C', 0, 0);
    }
    $pdf->Ln(10);
}

$pdf->Output('form.pdf', 'I');
?>
