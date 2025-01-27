<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Upload</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .container {
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .error {
            color: red;
        }
        .form-section {
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
        .file-label {
            display: inline-block;
            width: 100%;
            text-align: center;
        }
        .file-input {
            display: none;
        }
    </style>
</head>
<body>
<?php include 'nav.html'; ?>
<div class="container">
    <h2 class="text-center mb-4">Upload Your Documents</h2>

    <div class="form-section bg-light">
        <form id="uploadForm" method="post" enctype="multipart/form-data" action="student_documents_upload_backend.php">
            <div class="mb-3">
                <label for="aadhar_card" class="form-label">Aadhar Card (PDF)</label>
                <input type="file" class="form-control file-input" id="aadhar_card" name="aadhar_card" accept="application/pdf" required>
            </div>

            <div class="mb-3">
                <label for="marksheet_10th" class="form-label">10th Marksheet (PDF)</label>
                <input type="file" class="form-control file-input" id="marksheet_10th" name="marksheet_10th" accept="application/pdf" required>
            </div>

            <div class="mb-3">
                <label for="marksheet_12th" class="form-label">12th Marksheet (PDF)</label>
                <input type="file" class="form-control file-input" id="marksheet_12th" name="marksheet_12th" accept="application/pdf" required>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-primary btn-lg">Upload Documents</button>
            </div>
        </form>

        <p id="error-message" class="error mt-3 text-center"></p>
    </div>
</div>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"></script>

<script>
    document.getElementById('uploadForm').addEventListener('submit', function (e) {
        let valid = true;
        const maxSize = 5 * 1024 * 1024; // 5MB in bytes
        const inputs = document.querySelectorAll('input[type="file"]');
        const errorMessage = document.getElementById('error-message');
        errorMessage.textContent = '';

        inputs.forEach(input => {
            if (input.files[0].size > maxSize) {
                valid = false;
                errorMessage.textContent = 'Each file must be less than 5MB.';
            }
        });

        if (!valid) {
            e.preventDefault();
        }
    });
</script>

</body>
</html>
