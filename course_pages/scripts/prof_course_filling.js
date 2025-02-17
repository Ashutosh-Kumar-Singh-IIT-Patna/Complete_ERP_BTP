document.addEventListener('DOMContentLoaded', function() {
    console.log("JavaScript file loaded!");

    document.querySelector('form').addEventListener('submit', function (event) {
        const inputs = document.querySelectorAll('input[name^="grades"]');
        for (const input of inputs) {
            if (input.value && !/^[A-FN]{1,2}$/i.test(input.value)) {
                alert("Invalid grade entered. Use A-F, N, or leave blank.");
                event.preventDefault();
                return;
            }
        }
    });

    const uploadBtn = document.getElementById('uploadCsvBtn');
    const fileInput = document.getElementById('csvFileInput');
    const mappingModal = document.getElementById('mappingModal');
    const mappingOptions = document.getElementById('mappingOptions');
    const confirmMappingBtn = document.getElementById('confirmMapping');

    let csvData = [];
    let headers = [];

    uploadBtn.addEventListener('click', () => {
        console.log("Upload button clicked.");
        fileInput.click();
    });

    fileInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            console.log("File selected:", file.name);
            const reader = new FileReader();
            reader.onload = function(e) {
                console.log("File read successfully.");
                csvData = CSVToArray(e.target.result);
                if (csvData.length < 2) {
                    alert("Invalid or empty CSV file.");
                    return;
                }
                headers = csvData[0];
                showMappingModal();
            };
            reader.readAsText(file, "UTF-8");
        }
    });

    function showMappingModal() {
        mappingOptions.innerHTML = '';
        ['Roll Number', 'Grade'].forEach(field => {
            const select = document.createElement('select');
            select.id = `mapping_${field.toLowerCase().replace(' ', '_')}`;
            select.innerHTML = '<option value="">Select column for ' + field + '</option>' +
                headers.map((header, index) => `<option value="${index}">${header}</option>`).join('');
            mappingOptions.appendChild(select);
        });
        mappingModal.style.display = 'block';
    }

    confirmMappingBtn.addEventListener('click', function() {
        const rollMapping = document.getElementById('mapping_roll_number').value;
        const gradeMapping = document.getElementById('mapping_grade').value;
        if (rollMapping && gradeMapping) {
            updateGradesFromCSV(parseInt(rollMapping), parseInt(gradeMapping));
            mappingModal.style.display = 'none';
        } else {
            alert('Please select mapping for both Roll Number and Grade');
        }
    });

    function updateGradesFromCSV(rollIndex, gradeIndex) {
        console.log("Updating grades...");
        document.querySelectorAll('input[name^="grades["]').forEach(input => {
            csvData.slice(1).forEach(row => {
                const roll = row[rollIndex]?.trim();
                const grade = row[gradeIndex]?.trim().toUpperCase();
                if (roll && grade && input.name === `grades[${roll}]`) {
                    input.value = grade;
                }
            });
        });
    }

    function CSVToArray(strData, strDelimiter = ',') {
        return strData.split(/\r?\n/).map(line => line.split(strDelimiter).map(cell => cell.trim()));
    }
});
