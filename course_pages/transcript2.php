<?php

require_once 'TCPDF/tcpdf.php';
require_once 'utils/trans_utils.php';
session_start();
$rollno = $_SESSION['roll'];
ob_start(); // Start output buffering

$gradePoints = [
    'AU' => 10,
    'PP' => 10,
    'AA' => 10,
    'AB' => 9,
    'BB' => 8,
    'BC' => 7,
    'CC' => 6,
    'CD' => 5,
    'DD' => 4,
    'F' => 0,
    'I' => 0,
    'NP' => 0,
    'NU' => 0,
    'X' => 0
];
$studentData = getsemgrades($rollno);
$processedData = processStudentData($studentData, $gradePoints);

// echo '<pre>';
// print_r($processedData);
// echo '</pre>';

$semesters = array_unique(array_column($studentData, 'sem'));
$numSemesters = count($semesters);


$name = 'John Doe'; // Default value
$yearOfAdmission = '20' . substr($rollno, 0, 2);
$programMap = [
    '0' => 'Bachelor of Technology',
    '1' => 'Master of Technology',
    '2' => 'Doctor of Philosophy'
];
$program = isset($programMap[$rollno[2]]) ? $programMap[$rollno[2]] : 'Unknown Program';
$courseMap = [
    'CS' => 'Computer Science and Engineering',
    'CB' => 'Chemical and Biochemical Engineering',
    'CE' => 'Civil Engineering',
    'EE' => 'Electrical Engineering',
    'ME' => 'Mechanical Engineering',
    'MM' => 'Metallurgical and Materials Engineering',
    'AI' => 'Artificial Intelligence and Data Science',
    'MC' => 'Mathematics and Computing',
    'EP' => 'Engineering Physics',
    'CH' => 'Chemical Science and Technology',
];
$courseCode = substr($rollno, 4, 2);
$course = isset($courseMap[$courseCode]) ? $courseMap[$courseCode] : 'Unknown Course';
ob_end_clean(); // Clear any output before generating the PDF

class MYPDF extends TCPDF {
    public function Header() {
        // Show header only on the first page
        if ($this->page > 1){
            return;
        }

        // Adjust image scaling and positioning for A1 landscape (841mm width)
        $image_file_left = 'images/iitp.png';
        $image_file_right = 'images/iitp_header.png';

        // Left logo - increase size and shift right a little
        $this->Image(
            $image_file_left,
            40,        // x
            10,        // y
            40,        // width
            '',        // height auto
            'PNG',
            '',
            'N',
            false,
            300,
            '',
            false,
            false,
            0,
            false,
            false,
            false
        );

        // Right header banner - increase width, and position it toward the far right
        $this->Image(
            $image_file_right,
            600,       // x (towards right edge of 841mm page)
            12,        // y
            200,       // width
            '',        // height auto
            'PNG',
            '',
            'N',
            false,
            300,
            '',
            false,
            false,
            0,
            false,
            false,
            false
        );

        $this->SetFont('helvetica', 'B', 40); // Bold, larger font
        $this->SetTextColor(0, 0, 0);         // Black color

        // Get page width and set alignment for center
        $pageWidth = $this->getPageWidth();
        $this->SetXY(0, 25); // Adjust Y position below the logos
        $this->Cell($pageWidth, 15, 'INTERIM TRANSCRIPT', 0, 1, 'C', false, '', 0, false, 'T', 'C');
    }
}

$pdf = new MYPDF('L', 'mm', 'A1', true, 'UTF-8', false);

// // Remove default header and footer
// $pdf->setPrintHeader(false);
// $pdf->setPrintFooter(false);

// // Add page
// $pdf->AddPage();

// // Set font
// $pdf->SetFont('helvetica', '', 14);

// // Define the cell dimensions

// // Set cell padding to minimal
// $pdf->setCellPaddings(0, 0, 0, -5);

// // Set cell height ratio to minimal
// $pdf->setCellHeightRatio(1.0);

// // Define the HTML content for left and right parts
// $htmlContent = '
// <div style="text-align: center; color: black;">
//     <img src="utils/iitp_logo.jpg" alt="Logo" width="120" height="100">
//     <h2 style="font-size: 13px; margin: 0;">INTERIM TRANSCRIPT</h2>
//     <hr width="80%" style="margin: 0;">
// </div>';

// // Left part
// $pdf->writeHTMLCell($cellWidth, $cellHeight, $margin, '', $htmlContent, 1, 0, 0, true, 'C', true );

// // Set font
// // Middle content (no changes here)
// $middleContent = '
// <div style="text-align: center; color: black;">
//     <h1 style="font-size: 28px; margin: 0;">भारतीय प्रौद्योगिकी संस्थान पटना</h1>
//     <h2 style="font-size: 24px; margin: 0;">Indian Institute of Technology, Patna</h2>
//     <h2 style="font-size: 22px; margin: 0;">Transcript</h2>
// </div>';

// // Set the font with Unicode support (change to 'freeserif' or 'dejavusans')
// $pdf->SetFont('freeserif', '', 16);

// // Middle part (spanning the rest of the page width)
// $middleCellWidth = $pageWidth - 2 * ($margin + $cellWidth);
// $pdf->writeHTMLCell($middleCellWidth, $cellHeight, $margin + $cellWidth, '', $middleContent, 1, 0, 0, true, 'C', true);

// // Set font
// $pdf->SetFont('helvetica', '', 14);
// // Right part
// $pdf->writeHTMLCell($cellWidth, $cellHeight, $pageWidth - $margin - $cellWidth, '', $htmlContent, 1, 1, 0, true, 'C', true);

$pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('IITP');
    $pdf->SetTitle('Transcript');
    $pdf->SetSubject('Student Transcript');
    $pdf->SetKeywords('Marksheet, Transcript, IITP');
    $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
    $pdf->setPrintFooter(false);
    
    
    $margin_left = 20;
    $margin_right = 6;
    $margin_top = 6;
    $margin_bottom = 5;
    // $pdf->SetMargins($margin_left, $margin_top, $margin_right);
    $pdf->SetHeaderMargin($margin_top);
    $pdf->SetAutoPageBreak(TRUE, $margin_bottom);
    $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

    $pdf->SetFont('helvetica', '', 14);
    
    $pdf->AddPage();

    $margin = 10;
    $cellWidth = 55;
    $cellHeight = 60; // auto height
    $pageWidth = $pdf->getPageWidth();

    $pdf->setCellPaddings(1.5, 0.7, 0.8, 0.5);
    $pdf->Ln(20);

// Calculate the big cell dimensions
$bigCellWidth = $pageWidth - 2 * $margin;
$pageHeight = $pdf->getPageHeight();
$bottomMargin = 20; // Margin from the bottom where the big cell should end
$bigCellStartY = $margin + $cellHeight; // Y-coordinate where the big cell starts
$bigCellHeight = 500; // Calculate the height

// Write the big cell below the three tables
$pdf->writeHTMLCell($bigCellWidth, $bigCellHeight, $margin, $bigCellStartY, '', 1, 1, 0, true, 'L', true);

//additional Content
$additionalContent = '
<div style="color: black; text-align: center;">
    <p style="font-size: 20px; margin: 0; padding: 0;">
        Roll No: ' . $rollno . '  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Name: ' . $name . '  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Year of Admission: ' . $yearOfAdmission . '
    </p>
    <p style="font-size: 20px; margin: 0; padding: 0;">
        Programme: ' . $program . ' &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Course: ' . $course . '
    </p>
</div>';

$pdf->writeHTMLCell(400, 20, 220, 74, $additionalContent, 1, 1, 0, true, 'C', true);

function generateSemesterTable($semesterData, $semesterNumber) {
    $htmlTable = '
    <h1 style="font-weight: bold; text-decoration: underline; margin-bottom: 10px; font-size: 18px;">Semester ' . $semesterNumber . '</h1>
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: center; font-size: 12px;">
        <tr style="background-color: #f2f2f2;">
            <th style="width: 13%; text-align: center; font-weight: bold;">Course Code</th>
            <th style="width: 61%; text-align: center; font-weight: bold;">Course Name</th>
            <th style="width: 10%; text-align: center; font-weight: bold;">L-T-P</th>
            <th style="width: 8%; text-align: center; font-weight: bold;">CRD</th>
            <th style="width: 8%; text-align: center; font-weight: bold;">GRD</th>
        </tr>';

    foreach ($semesterData as $subject) {
        $subjectDetails = getSubjectDetails($subject['course_code']);
        $subname = $subjectDetails['course_name'];
        $ltp = $subjectDetails['l-t-p'];
        $htmlTable .= '
        <tr>
            <td style="text-align: center; font-size: 12px; font-weight: bold; padding: 10px;">' . $subject['course_code'] . '</td>
            <td style="text-align: center; font-size: 12px; font-weight: bold; padding: 10px;">' . $subname . '</td>
            <td style="text-align: center; font-size: 12px; font-weight: bold; padding: 10px;">' . $ltp . '</td>
            <td style="text-align: center; font-size: 12px; font-weight: bold; padding: 10px;">' . $subject['c'] . '</td>
            <td style="text-align: center; font-size: 12px; font-weight: bold; padding: 10px;">' . $subject['grade'] . '</td>
        </tr>';
    }

    $htmlTable .= '</table>';
    return $htmlTable;
}

// Generate tables for each semester
$semesterTables = [];
foreach ($semesters as $semester) {
    $semesterData = array_filter($studentData, function($subject) use ($semester) {
        return $subject['sem'] == $semester;
    });
    $semesterTables[] = generateSemesterTable($semesterData, $semester);
}

// Set table positions and dimensions
$tableX = 15; // X-coordinate for the first table
$tableY = 125; // Y-coordinate for all tables
$tableWidth = ($pageWidth - 2 * $tableX - 20) / 3; // Table width divided by 3 for side by side with extra space in between
$tableHeight = 120; // Adjust this height as needed

// Set spacing between tables
$spacing = 10;
$currentY = $tableY;

for ($i = 0; $i < $numSemesters; $i += 3) {
    // Determine the number of tables in the current row
    $tablesInRow = min(3, $numSemesters - $i);

    // Write the tables for each semester side by side within the big cell
    for ($j = 0; $j < $tablesInRow; $j++) {
        $pdf->writeHTMLCell($tableWidth, $tableHeight, $tableX + $j * ($tableWidth + $spacing), $currentY, $semesterTables[$i + $j], 0, 0, 0, true, 'L', true);
    }

    // Move to the next row
    $currentY += $tableHeight + 3;

    // Write the details below each table
    $detailsFontSize = 16; // Font size for the details
    $detailsHeight = 10; // Increased height for the details to fit the content better
    $pdf->SetFont('helvetica', '', $detailsFontSize); // Set the font size for details

    for ($j = 0; $j < $tablesInRow; $j++) {
        $semesterNumber = $i + $j + 1;
        $details = $processedData[$semesterNumber]; // Extract details from processedData

        $spi = number_format((float)$details['spi'], 2, '.', '');
        $cpi = number_format((float)$details['cpi'], 2, '.', '');

        $detailsText = 'Credits Taken: ' . $details['credits_taken'] . ' &nbsp; &nbsp; Credits Cleared: ' . $details['credits_cleared'] . ' &nbsp; &nbsp; SPI: ' . $spi . ' &nbsp; &nbsp; CPI: ' . $cpi;
        $pdf->writeHTMLCell($tableWidth, $detailsHeight, $tableX + $j * ($tableWidth + $spacing), $currentY, $detailsText, 1, 0, 0, true, 'C', true);
    }

    // Move to the next row
    $currentY += $detailsHeight + 5;

    // Draw a bottom line after each row of tables
    $pdf->Line($margin, $currentY, $margin + $bigCellWidth, $currentY);

    $currentY += 7;

}

// Output
$pdf->Output('interim_transcript.pdf', 'I');
