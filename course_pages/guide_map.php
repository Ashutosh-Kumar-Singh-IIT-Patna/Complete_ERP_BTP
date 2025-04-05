<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Guide Map Form</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background: #f2f4f8;
      margin: 0;
      padding: 0;
    }

    .container {
      max-width: 600px;
      margin: 40px auto;
      background: white;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 8px rgba(0,0,0,0.1);
    }

    h2 {
      text-align: center;
      margin-bottom: 25px;
    }

    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
    }

    input[type="text"] {
      width: 100%;
      padding: 10px;
      margin-top: 5px;
      margin-bottom: 15px;
      border-radius: 5px;
      border: 1px solid #ccc;
    }

    button {
      padding: 12px;
      background-color: #007BFF;
      color: white;
      font-size: 16px;
      border: none;
      border-radius: 5px;
      width: 100%;
      cursor: pointer;
    }

    button:hover {
      background-color: #0056b3;
    }

    .info-box {
      background-color: #f1f1f1;
      padding: 15px;
      margin-top: 15px;
      border-left: 5px solid #007BFF;
    }

    @media (max-width: 600px) {
      .container {
        padding: 20px;
        margin: 20px 10px;
      }

      h2 {
        font-size: 20px;
      }
    }
  </style>
</head>
<body>
  <div class="container">
    <h2>Guide Map Form</h2>

    <div id="form-section">
      <label for="rollno">Enter Roll Number:</label>
      <input type="text" id="rollno" placeholder="e.g. 2101CS01" />
      <button onclick="fetchStudentDetails()">Proceed</button>
    </div>

    <div id="details-section" style="display: none;">
      <div class="info-box" id="student-info"></div>

      <label for="supervisor">Supervisor Name:</label>
      <input type="text" id="supervisor" placeholder="Enter Supervisor's Name" />

      <label for="cosupervisor">Co-Supervisor Name:</label>
      <input type="text" id="cosupervisor" placeholder="Enter Co-Supervisor's Name (Optional)" />

      <button onclick="submitForm()">Submit</button>
    </div>

    <div id="result-section" style="display: none;">
      <div class="info-box" id="submitted-info"></div>
    </div>
  </div>

  <script>
    const dummyData = {
      "2101CS01": {
        name: "Alice Sharma",
        program: "B.Tech",
        department: "Computer Science"
      },
      "2101EE01": {
        name: "Ravi Kumar",
        program: "Ph.D.",
        department: "Electrical Engineering"
      }
    };

    function fetchStudentDetails() {
      const roll = document.getElementById("rollno").value.trim();
      const detailsSection = document.getElementById("details-section");
      const studentInfo = document.getElementById("student-info");

      if (dummyData[roll]) {
        const data = dummyData[roll];
        studentInfo.innerHTML = `
          <p><strong>Name:</strong> ${data.name}</p>
          <p><strong>Roll No:</strong> ${roll}</p>
          <p><strong>Program:</strong> ${data.program}</p>
          <p><strong>Department:</strong> ${data.department}</p>
        `;
        detailsSection.style.display = "block";
      } else {
        studentInfo.innerHTML = `<p style="color:red;"><strong>No student found for Roll No: ${roll}</strong></p>`;
        detailsSection.style.display = "block";
      }
    }

    function submitForm() {
      const roll = document.getElementById("rollno").value.trim();
      const supervisor = document.getElementById("supervisor").value.trim();
      const cosupervisor = document.getElementById("cosupervisor").value.trim();
      const student = dummyData[roll];

      const output = `
        <p><strong>Name:</strong> ${student?.name || 'N/A'}</p>
        <p><strong>Roll No:</strong> ${roll}</p>
        <p><strong>Supervisor:</strong> ${supervisor}</p>
        <p><strong>Co-Supervisor:</strong> ${cosupervisor || '-'}</p>
      `;

      document.getElementById("submitted-info").innerHTML = output;
      document.getElementById("result-section").style.display = "block";
    }
  </script>
</body>
</html>
