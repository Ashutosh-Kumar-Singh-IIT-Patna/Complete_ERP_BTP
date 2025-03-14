<?php
require_once('../course_pages/TCPDF/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        // Add logo (Make sure 'logo.png' exists in the same directory)
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
$pdf->SetFont('times', '', 11.5);

// Scholar Info Table (Now Grouped Name & Roll No.)
$pdf->Ln(20);
$pdf->SetDrawColor(230, 230, 230); // Lightest border
foreach ([
    ['Name Of The Scholar', 'King Kumar', 'Roll No', '2221cs99'],
    ['Date Of Birth', '17 July 2022', 'Department', 'Computer Science And Engineering'],
    ['Nationality', 'Indian', 'Scholar Category', 'Regular & Full-Time (Institute Fellow)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[1], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[2], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[3], 1, 1, 'L');
}

// Supervisor & Co-Supervisor Table
$pdf->Ln(3);
foreach ([
    ['SUPERVISOR', 'Mayank Agarwal', 'Co-SUPERVISOR', 'Not Applicable (NA/NA)']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[1], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[2], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[3], 1, 1, 'L');
}

// If Sponsored & Research Area Table
$pdf->Ln(3);
foreach ([
    ['If Sponsored', '##########', 'Broad Area of Research', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[1], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[2], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[3], 1, 1, 'L');
}

// Title of Form & DC Formation Table
$pdf->Ln(3);
foreach ([
    ['Title of Form', '##########', 'Date of DC Formation', '##########']
] as $row) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[0], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[1], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(50, 8, $row[2], 1, 0, 'L');
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(50, 8, $row[3], 1, 1, 'L');
}

// Date Grid (50% Dark Border)
$pdf->Ln(3);
$pdf->SetFont('times', '', 10);
$pdf->SetDrawColor(120, 120, 120); // 50% Darker border
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

// Signature Grid (25% Dark Border)
$pdf->Ln(5);
$pdf->SetFont('times', '', 10);
$pdf->SetDrawColor(180, 180, 180); // 25% Darker border
$signatures = [
    'Chairperson', 'Supervisor', 'Co-Supervisor', 'Member 1', 'Member 2',
    'Section Head', 'Staff Representative', 'Junior Researcher', 'Academic Registrar', 'Dean'
];

foreach (array_chunk($signatures, 5) as $row) {
    foreach ($row as $sig) {
        $pdf->Cell(40, 14, '', 1, 0, 'C');
    }
    $pdf->Ln(14);
    foreach ($row as $sig) {
        $pdf->Cell(40, 8, $sig, 1, 0, 'C');
    }
    $pdf->Ln(10);
}

$pdf->Output('form.pdf', 'I');
?>
