<?php
require_once('../course_pages/TCPDF/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
        $this->SetFont('times', 'B', 18);
        $this->Cell(0, 12, 'IIT Patna', 0, 1, 'C');
        $this->SetFont('times', '', 12);
        $this->Cell(0, 12, 'Form Name', 0, 1, 'C');
        $this->Ln(5);
    }
}

$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetMargins(15, 20, 15);
$pdf->AddPage();
$pdf->SetFont('times', '', 12);

$fields = [
    '1. Name Of The Scholar' => 'King Kumar',
    '2. Roll No' => '2221cs99',
    '3. Date Of Birth' => '17 July 2022',
    '4. Department' => 'Computer Science And Engineering',
    '5. Nationality' => 'Indian',
    '6. Scholar Category' => 'Regular & Full-Time (Institute Fellow)',
    '7. SUPERVISOR' => 'Mayank Agarwal',
    '8. Co-SUPERVISOR' => 'Not Applicable (NA/NA)',
    '9. If Working In A Scheme / Project' => '##########',
    '   a) Project Number as Per RnD' => '##########',
    '   b) Project Name as Per RnD' => '##########',
    '   c) Project Tenure' => '##########',
    '10. If Sponsored' => 'Name Of The Sponsoring Agency: ##########',
    '11. Broad Area of Research' => '########',
    '12. Title of Form' => '########',
    '13. Date of DC Formation' => '##########'
];

foreach ($fields as $key => $value) {
    $pdf->SetTextColor(0, 0, 255);
    $pdf->Cell(90, 12, $key, 0, 0);
    $pdf->SetTextColor(0, 0, 0);
    $pdf->Cell(90, 12, $value, 0, 1);
}

$pdf->Ln(5);
$pdf->Cell(0, 12, 'Important Dates:', 0, 1, 'C');
$pdf->Ln(5);

$dates = [
    'DCFormation', 'Enrollment', 'CompExamCom', 'CompExamRpt', 'CompExamRpt2',
    'RegSem', 'RegSem2', 'AssistEnhance', 'APS-1', 'APS-2',
    'APS-3', 'APS-4', 'APS-5', 'SynopsisSub', 'SynopsisSubRpt',
    'SynopsisSem', 'PanelExam', 'ThesisRpt', 'VivaReco', 'FinalReco'
];

$colWidth = 38;
$colHeight = 10;
$colCount = 5;
foreach (array_chunk($dates, $colCount) as $row) {
    foreach ($row as $date) {
        $pdf->SetTextColor(0, 0, 255);
        $pdf->Cell($colWidth, $colHeight, $date, 1, 0, 'C');
    }
    $pdf->Ln($colHeight);
    foreach ($row as $date) {
        $pdf->SetTextColor(0, 0, 0);
        $pdf->Cell($colWidth, $colHeight, '_________', 1, 0, 'C');
    }
    $pdf->Ln($colHeight);
}

$pdf->Ln(10);
$pdf->Cell(0, 12, 'Signatures:', 0, 1);
$signatures = [
    'Chairperson', 'Supervisor', 'Co-Supervisor', 'Member 1', 'Member 2',
    'Section Head', 'Staff Representative', 'Junior Researcher', 'Academic Registrar', 'Dean'
];

foreach (array_chunk($signatures, 5) as $row) {
    foreach ($row as $sig) {
        $pdf->Cell(42, 16, '', 0, 0, 'C');
    }
    $pdf->Ln(16);
    foreach ($row as $sig) {
        $pdf->Cell(42, 16, $sig, 0, 0, 'C');
    }
    $pdf->Ln(16);
}

$pdf->Output('form.pdf', 'I');
?>