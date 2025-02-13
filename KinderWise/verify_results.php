<?php
session_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Student Results</title>
    <style>
        /* Reusing your existing styles */
        body {
            font-family: Trebuchet MS, sans-serif;
            text-align: center;
            margin: 0;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: rgb(255, 255, 255);
            padding: 15px 30px;
            color: white;
        }

        .logo img {
            width: 150px;
        }

        .top-right-buttons button {
            font-family: Trebuchet MS, sans-serif;
            background: none;
            border: none;
            color: black;
            font-size: 16px;
            margin-left: 15px;
            cursor: pointer;
        }

        .content {
            display: flex;
            min-height: 100vh;
        }

        .navbar {
            width: 300px;
            background: rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            height: auto;
        }

        .navbar li {
            height: 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: flex-start;
            padding: 0px 15px;
            margin: 10px 10px;
        }

        .navbar li a {
            color: #202224;
        }

        .navbar li:hover {
            box-shadow: inset 600px 0 0 0 rgb(185, 204, 255);
            cursor: pointer;
            transition: ease-out 0.9s;
        }

        .navbar a button {
            font-family: Trebuchet MS, sans-serif;
            padding: 20px 80px;
            margin: 40px;
            font-size: 16px;
            cursor: pointer;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 5px;
            transition: 0.3s;
        }

        .navbar a button:hover {
            background-color: #3b5fc9;
        }

        a:link { text-decoration: none; }
        a:visited { text-decoration: none; }
        a:hover { text-decoration: none; }
        a:active { text-decoration: none; }

        .menu {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .pages {
            display: flex;
            flex-direction: column;
        }

        #currentPage {
            background-color: rgb(185, 204, 255);
            padding: 10px;
        }

        .divider {
            height: 1px;
            background: rgb(213, 213, 213);
            margin: 10px 15px;
        }

        .section-title {
            color: rgb(198, 198, 198);
            font-size: 14px;
            text-align: left;
            padding-top: 10px;
            padding-left: 20px;
            margin-bottom: 5px;
        }

        .container {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            flex-grow: 1;
            padding: 20px 50px;
        }


        .filters {
            display: flex;
            gap: 10px;
            margin-bottom: 20px;
            align-items: center;
        }

        .view-results-btn {
            padding: 8px 20px;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        .view-results-btn:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }

        .view-results-btn:hover:not(:disabled) {
            background-color: #3b5fc9;
        }

        .student-info {
            background: #86abff;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            width: auto;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .student-picture img {
            width: 100px;
            margin-left: 50px;
        }

        .student-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 50px;
        }

        .student-details h2 {
            margin: 0;
            color: white;
        }

        .student-details p {
            margin: 5px 0;
            color: white;
        }

        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
            overflow: hidden; 
        }

        .results-table th, 
        .results-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .student-select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-top: 20px;
            width: 100%;
        }

        .results-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .results-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .status-verified {
            background-color: #4caf50;
            color: white;
        }

        .status-unverified {
            background-color: #ff9800;
            color: white;
        }

        .verify-btn {
            padding: 10px 20px;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 20px;
        }

        .verify-btn:hover {
            background-color: #3b5fc9;
        }

        .result-info {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <header>
        <div class="logo">
            <img src="./pics/logo.png" alt="KinderWise Logo">
        </div>
        <div class="top-right-buttons">
            <button>🔔 Notifications</button>
            <button>👤 Profile</button>
        </div>
    </header>
    
    <div class="content">
        <div class="navbar">
            <nav>
                <a href="principal.php"><button>Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
                <div class="pages">
                    <li><a href="student_performance.php">📊 View Student Performance</a></li>
                    <li><a href="track_assessments.php">⏱️ Track Assessment Submission</a></li>
                    <li><a href="verify_results.php"><div id="currentPage">📝 Verify Student Results</div></a></li>
                    <br></br>
                    <br></br>
                </div>

                <div class="divider"></div>
                
                <div class="bottom">
                    <li>⚙️ Settings</li>
                    <li><a href="logout.php">↩️ Logout</a></li>
                </div>
            </nav>
        </div>

        <div class="container">
            <h1>Verify Student Results</h1>
            
            <div class="filters">
                <select id="classSelect" onchange="handleFilterChange()">
                    <option value="">Select Class</option>
                    <?php
                    $classQuery = "SELECT DISTINCT classCode FROM class ORDER BY classCode";
                    $classResult = mysqli_query($conn, $classQuery);
                    while($row = mysqli_fetch_assoc($classResult)) {
                        echo "<option value='" . $row['classCode'] . "'>" . $row['classCode'] . "</option>";
                    }
                    ?>
                </select>

                <select id="assessmentTypeSelect" onchange="handleFilterChange()" disabled>
                    <option value="">Select Assessment Type</option>
                    <option value="Midterm">Midterm</option>
                    <option value="Finals">Finals</option>
                </select>

                <select id="studentSelect" onchange="handleFilterChange()" disabled>
                    <option value="">Select Student</option>
                </select>

                <button id="viewResultsBtn" onclick="validateAndFetchResults()" class="view-results-btn" disabled>
                    View Results
                </button>
            </div>

            <div class="result-info" id="resultInfo">
                <!-- Student info and result table will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function handleFilterChange() {
            const classCode = document.getElementById("classSelect").value;
            const assessmentType = document.getElementById("assessmentTypeSelect").value;
            const studentSelect = document.getElementById("studentSelect");
            const viewResultsBtn = document.getElementById("viewResultsBtn");

            // Reset result info
            document.getElementById("resultInfo").innerHTML = "";

            // Handle class selection
            if (!classCode) {
                document.getElementById("assessmentTypeSelect").disabled = true;
                studentSelect.disabled = true;
                viewResultsBtn.disabled = true;
                return;
            }

            // Enable assessment type select
            document.getElementById("assessmentTypeSelect").disabled = false;

            // Only fetch students when class selection changes
            if (event && event.target.id === "classSelect") {
                fetch(`fetch_students.php?classCode=${encodeURIComponent(classCode)}`)
                    .then(response => response.json())
                    .then(data => {
                        studentSelect.innerHTML = '<option value="">Select Student</option>';
                        data.forEach(student => {
                            studentSelect.innerHTML += `<option value="${student.studentID}">${student.name}</option>`;
                        });
                        studentSelect.disabled = false;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Error loading students');
                    });
            }

            // Check if all filters are selected
            checkFilters();
        }

        function checkFilters() {
            const classCode = document.getElementById("classSelect").value;
            const assessmentType = document.getElementById("assessmentTypeSelect").value;
            const studentID = document.getElementById("studentSelect").value;
            const viewResultsBtn = document.getElementById("viewResultsBtn");

            // Enable view results button only if all filters are selected
            viewResultsBtn.disabled = !(classCode && assessmentType && studentID);
        }

        function validateAndFetchResults() {
            const classCode = document.getElementById("classSelect").value;
            const assessmentType = document.getElementById("assessmentTypeSelect").value;
            const studentID = document.getElementById("studentSelect").value;

            if (!classCode || !assessmentType || !studentID) {
                alert("Please select all filters");
                return;
            }

            fetchStudentResults();
        }

        
        function fetchStudents() {
            const classCode = document.getElementById("classSelect").value;
            const studentSelect = document.getElementById("studentSelect");
            const studentSelectContainer = document.getElementById("studentSelectContainer");
            const assessmentTypeSelect = document.getElementById("assessmentTypeSelect");
            
            if (!classCode) {
                studentSelectContainer.style.display = 'none';
                assessmentTypeSelect.disabled = true;
                document.getElementById("resultInfo").innerHTML = "";
                return;
            }

            fetch(`fetch_students.php?classCode=${encodeURIComponent(classCode)}`)
                .then(response => response.json())
                .then(data => {
                    studentSelect.innerHTML = '<option value="">Select Student</option>';
                    data.forEach(student => {
                        studentSelect.innerHTML += `<option value="${student.studentID}">${student.name}</option>`;
                    });
                    studentSelectContainer.style.display = 'block';
                    assessmentTypeSelect.disabled = false;
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error loading students');
                });
        }
        

        function fetchStudentResults() {
            const studentID = document.getElementById("studentSelect").value;
            const assessmentType = document.getElementById("assessmentTypeSelect").value;

            fetch(`fetch_student_results.php?studentID=${encodeURIComponent(studentID)}&assessmentType=${encodeURIComponent(assessmentType)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        document.getElementById("resultInfo").innerHTML = `<p>Error: ${data.error}</p>`;
                        return;
                    }

                    let html = `
                        <div class="student-info">
                            <div class="student-picture">
                                <img src="./pics/student.png" alt="Student Picture">
                            </div>
                            <div class="student-details">
                                <h2>${data.studentInfo.name}</h2>
                                <p>Class: ${data.studentInfo.classCode}</p>
                            </div>
                        </div>
                    `;

                    if (data.results.length > 0) {
                        html += `
                            <table class="results-table">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Subject</th>
                                        <th>Assessment</th>
                                        <th>Result</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        data.results.forEach((result, index) => {
                            html += `
                                <tr>
                                    <td>${index + 1}</td>
                                    <td>${result.subjectName}</td>
                                    <td>${result.assessmentType}</td>
                                    <td>${result.finalScore}</td>
                                    <td>${result.status}</td>
                                </tr>
                            `;
                        });

                        html += `
                                </tbody>
                            </table>
                            <button onclick="verifyAllResults()" class="verify-btn">Verify All Results</button>
                        `;
                    } else {
                        html += '<p>No results found for this student.</p>';
                    }

                    document.getElementById("resultInfo").innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById("resultInfo").innerHTML = "Error loading data. Please try again.";
                });
        }

        function verifyAllResults() {
            const studentID = document.getElementById("studentSelect").value;
            const assessmentType = document.getElementById("assessmentTypeSelect").value;

            if (confirm('Are you sure you want to verify all results? This action cannot be undone.')) {
                fetch('verify_all_results.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: `studentID=${encodeURIComponent(studentID)}&assessmentType=${encodeURIComponent(assessmentType)}`
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('All results have been verified successfully!');
                        fetchStudentResults(); // Refresh the results
                    } else {
                        alert('Error verifying results: ' + data.error);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error verifying results');
                });
            }
        }

         // Update event listeners for all select elements
        document.getElementById("classSelect").addEventListener("change", checkFilters);
        document.getElementById("assessmentTypeSelect").addEventListener("change", checkFilters);
        document.getElementById("studentSelect").addEventListener("change", checkFilters);

    </script>
</body>
</html>