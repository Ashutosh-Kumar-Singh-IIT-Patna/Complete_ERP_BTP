<?php
require_once '../functions.php';

$course_options = get_dropdown_options('acad_course');
$course = sanitize_input($_GET['course'] ?? '');
$year_of_admission = sanitize_input($_GET['year_of_admission'] ?? '');
$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($course) && !empty($year_of_admission)) {
    $sql = "SELECT roll_number, full_name, webmail, mobile_no, whatsapp_no FROM acad_users WHERE course = ? AND YEAR(date_of_admission) = ?";
    $params = ['ss', $course, $year_of_admission];

    $query_result = execute_query($sql, $params);
    if ($query_result['success']) {
        while ($row = $query_result['result']->fetch_assoc()) {
            $students[] = $row;
        }
    }
}

// Check if it's an AJAX request
if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    echo json_encode(['students' => $students]);
    exit();
}

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] == 'csv') {
    export_to_csv($students);
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Search</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            padding: 20px;
        }
        table {
            margin-top: 20px;
        }
        .form-container {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 class="text-center mb-4">Search for Students</h1>
        <div class="form-container">
            <form id="searchForm" class="d-flex justify-content-center">
                <div class="input-group">
                    <select name="course" id="course" class="form-select" required>
                        <option value="">Select Course</option>
                        <?php foreach ($course_options as $option): ?>
                            <option value="<?= htmlspecialchars($option['value']); ?>"><?= htmlspecialchars($option['value']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <input type="number" name="year_of_admission" id="year_of_admission" class="form-control" placeholder="Year of Admission" min="1900" max="2099" required>
                    <button type="submit" class="btn btn-primary">Search</button>
                    <button type="button" id="exportCsv" class="btn btn-secondary">Export as CSV</button>
                </div>
                <input type="hidden" name="export" id="export" value="">
            </form>
        </div>
        <div id="resultsContainer">
            <!-- The initial load will have no results -->
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#searchForm').on('submit', function(e) {
                e.preventDefault();
                searchStudents();
            });

            $('#exportCsv').on('click', function() {
                exportToCsv();
            });
        });

        function searchStudents() {
            $.ajax({
                url: 'generate_roll_list_year_wise.php',
                type: 'GET',
                data: $('#searchForm').serialize(),
                success: function(response) {
                    const data = JSON.parse(response);
                    const students = data.students;
                    let resultsHtml = '';

                    if (students.length > 0) {
                        resultsHtml = 
                            `<h2>Search Results:</h2>
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Roll Number</th>
                                        <th>Name</th>
                                        <th>Webmail</th>
                                        <th>Phone Number</th>
                                        <th>Whatsapp Number</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                        
                        students.forEach(student => {
                            resultsHtml += 
                                `<tr>
                                    <td>${student.roll_number}</td>
                                    <td>${student.full_name}</td>
                                    <td>${student.webmail}</td>
                                    <td>${student.mobile_no}</td>
                                    <td>${student.whatsapp_no}</td>
                                </tr>`;
                        });

                        resultsHtml += 
                                `</tbody>
                            </table>`;
                    } else {
                        resultsHtml = '<p>No results found.</p>';
                    }

                    $('#resultsContainer').html(resultsHtml);
                }
            });
        }

        function exportToCsv() {
            const form = $('<form>', {
                'method': 'GET',
                'action': 'generate_roll_list_year_wise.php'
            }).append(
                $('<input>', {
                    'type': 'hidden',
                    'name': 'course',
                    'value': $('#course').val()
                }),
                $('<input>', {
                    'type': 'hidden',
                    'name': 'year_of_admission',
                    'value': $('#year_of_admission').val()
                }),
                $('<input>', {
                    'type': 'hidden',
                    'name': 'export',
                    'value': 'csv'
                })
            );

            $('body').append(form);
            form.submit();
            form.remove();
        }
    </script>
</body>
</html>
