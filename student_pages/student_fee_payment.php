

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fee Portal</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <h2>Fee Details</h2>
    <canvas id="feeChart"></canvas>
    <form id="uploadForm" enctype="multipart/form-data">
        <input type="file" name="document" required>
        <button type="submit">Upload Document</button>
    </form>
    <h2>Fee Payment</h2>
    <button onclick="pay('direct')">Pay Direct</button>
    <button onclick="pay('loan')">Pay via Loan</button>
    <div id="loanUpload" style="display:none;">
        <form id="loanForm" enctype="multipart/form-data">
            <input type="file" name="receipt" required>
            <button type="submit">Upload Loan Receipt</button>
        </form>
    </div>
    <script>
        const rollNumber = new URLSearchParams(window.location.search).get('roll_number');
        
        function fetchFeeDetails() {
            $.get(`student_get_fee_details_backend.php?roll_number=${rollNumber}`, function(data) {
                let feeData = JSON.parse(data);
                if (feeData.error) {
                    alert(feeData.error);
                } else {
                    renderChart(feeData);
                }
            });
        }
        
        function renderChart(feeData) {
            new Chart(document.getElementById('feeChart'), {
                type: 'bar',
                data: {
                    labels: ['Total Fee', 'Insurance + Mess', 'Advance Paid', 'Balance Due'],
                    datasets: [{
                        label: 'Fee Breakdown',
                        data: [feeData.fee_payable, feeData.fee_insurance_plus_mess_fee, feeData.advance_fee_paid, feeData.balance_fee_payable],
                        backgroundColor: ['blue', 'orange', 'green', 'red']
                    }]
                }
            });
        }
        
        function pay(mode) {
            if (mode === 'loan') {
                document.getElementById('loanUpload').style.display = 'block';
            } else {
                $.post('student_process_payment_backend.php', { roll_number: rollNumber, mode: 'direct' }, function(response) {
                    alert('Payment Successful! Transaction ID: ' + JSON.parse(response).transaction_id);
                });
            }
        }
        
        $('#uploadForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('roll_number', rollNumber);
            $.ajax({
                url: 'student_upload_fee_document_backend.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Document uploaded successfully');
                }
            });
        });

        $('#loanForm').submit(function(e) {
            e.preventDefault();
            let formData = new FormData(this);
            formData.append('roll_number', rollNumber);
            formData.append('mode', 'loan');
            $.ajax({
                url: 'student_process_payment_backend.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    alert('Loan receipt uploaded successfully. Transaction ID: ' + JSON.parse(response).transaction_id);
                }
            });
        });
        
        fetchFeeDetails();
    </script>
</body>
</html>
