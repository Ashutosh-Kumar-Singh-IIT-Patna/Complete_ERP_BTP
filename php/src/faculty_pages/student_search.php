<?php
require_once '../functions.php';

$search_by = sanitize_input($_GET['search_by'] ?? '');
$search_term = sanitize_input($_GET['search_term'] ?? '');
$students = [];

if ($_SERVER['REQUEST_METHOD'] === 'GET' && !empty($search_term)) {
    if ($search_by === 'roll_number') {
        $sql = "SELECT roll_number, full_name, webmail, mobile_no, whatsapp_no FROM acad_users WHERE roll_number = ?";
        $params = ['s', $search_term];
    } elseif ($search_by === 'name') {
        $sql = "SELECT roll_number, full_name, webmail, mobile_no, whatsapp_no FROM acad_users WHERE full_name LIKE ?";
        $params = ['s', '%' . $search_term . '%'];
    } elseif ($search_by === 'course') {
        $sql = "SELECT roll_number, full_name, webmail, mobile_no, whatsapp_no FROM acad_users WHERE course = ?";
        $params = ['s', $search_term];
    } elseif ($search_by === 'year_of_admission') {
        $sql = "SELECT roll_number, full_name, webmail, mobile_no, whatsapp_no FROM acad_users WHERE YEAR(date_of_admission) = ?";
        $params = ['i', $search_term];
    }

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
                    <select name="search_by" id="search_by" class="form-select" required>
                        <option value="roll_number">Roll Number</option>
                        <option value="name">Name</option>
                        <option value="course">Course</option>
                        <option value="year_of_admission">Year of Admission</option>
                    </select>
                    <input type="text" name="search_term" id="search_term" class="form-control" placeholder="Enter search term" required>
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
                url: 'student_search.php',
                type: 'GET',
                data: $('#searchForm').serialize(),
                success: function(response) {
                    const data = JSON.parse(response);
                    const students = data.students;
                    let resultsHtml = '';

                    if (students.length > 0) {
                        resultsHtml = `
                            <h2>Search Results:</h2>
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
                                <tbody>
                        `;

                        students.forEach(student => {
                            resultsHtml += `
                                <tr>
                                    <td>${student.roll_number}</td>
                                    <td>${student.full_name}</td>
                                    <td>${student.webmail}</td>
                                    <td>${student.mobile_no}</td>
                                    <td>${student.whatsapp_no}</td>
                                </tr>
                            `;
                        });

                        resultsHtml += `
                                </tbody>
                            </table>
                        `;
                    } else {
                        resultsHtml = '<p>No results found.</p>';
                    }

                    $('#resultsContainer').html(resultsHtml);
                }
            });
        }

        function exportToCsv() {
            const searchBy = $('#search_by').val();
            const searchTerm = $('#search_term').val();

            // Create a hidden form for the export
            const form = $('<form>', {
                'method': 'GET',
                'action': 'student_search.php'
            }).append(
                $('<input>', {
                    'type': 'hidden',
                    'name': 'search_by',
                    'value': searchBy
                }),
                $('<input>', {
                    'type': 'hidden',
                    'name': 'search_term',
                    'value': searchTerm
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
