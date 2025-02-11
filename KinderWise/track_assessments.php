<?php
session_start();
include("connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Track Assessment Submission</title>
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

        .bottom {
            margin-top: auto;
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

        .class-info {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            margin: 20px 0;
            width: 100%;
        }

        .teacher-info {
            background: #86abff;
            color: white;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 10px;
            display: flex;
            flex-direction: row;
            justify-content: flex-start;
            align-items: center;
        }

        .teacher-picture img {
            width: 100px;
            margin-left: 50px;
        }

        .teacher-details {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            margin-left: 50px;
        }

        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .assessment-table th, 
        .assessment-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .assessment-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .assessment-table tr:hover {
            background-color: #f5f5f5;
        }

        .status-badge {
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 0.9em;
        }

        .status-submitted {
            background-color: #4caf50;
            color: white;
        }

        .status-overdue {
            background-color: #f44336;
            color: white;
        }

        select {
            padding: 8px;
            border-radius: 5px;
            border: 1px solid #ddd;
            margin-right: 10px;
        }

        button {
            padding: 8px 15px;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #3b5fc9;
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
                    <li><a href="track_assessments.php"><div id="currentPage">⏱️ Track Assessment Submission</div></a></li>
                    <li><a href="verify_results.php">📝 Verify Student Results</a></li>
                    <br></br>
                    <br></br>
                </div>

                <div class="divider"></div>
                
                <div class="bottom">
                    <li>⚙️ Settings</li>
                    <li>↩️ Logout</li>
                </div>
            </nav>
        </div>

        <div class="container">
            <h1>Track Assessment Submission</h1>
            
            <?php
            // Get all classes
            $classQuery = "SELECT DISTINCT classCode FROM class ORDER BY classCode";
            $classResult = mysqli_query($conn, $classQuery);
            ?>

            <div class="filters">
                <select id="classSelect">
                    <option value="">Select Class</option>
                    <?php while($row = mysqli_fetch_assoc($classResult)) { ?>
                        <option value="<?php echo $row['classCode']; ?>"><?php echo $row['classCode']; ?></option>
                    <?php } ?>
                </select>
                <button onclick="fetchAssessments()">View Assessments</button>
            </div>

            <div class="class-info" id="classInfo">
                <!-- Teacher info and assessment table will be loaded here -->
            </div>
        </div>
    </div>

    <script>
        function fetchAssessments() {
            const classVal = document.getElementById("classSelect").value;
            
            if (!classVal) {
                alert("Please select a class");
                return;
            }
            
            // Show loading state
            document.getElementById("classInfo").innerHTML = "Loading...";
            
            const url = `fetch_assessments.php?class=${encodeURIComponent(classVal)}`;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.error) {
                        document.getElementById("classInfo").innerHTML = `<p>Error: ${data.error}</p>`;
                        return;
                    }

                    const teacher = data.teacher;
                    const assessments = data.assessments;

                    let html = `
                        <div class="teacher-info">
                            <div class="teacher-picture">
                                <img src="./pics/teacher.png" alt="Teacher Picture">
                            </div>
                            <div class="teacher-details">
                                <p>Class Teacher:   ${teacher.name}</p>
                                <p>Email:   ${teacher.email}</p>
                                <p>Contact:   ${teacher.contactNumber}</p>
                            </div>
                        </div>
                    `;

                    if (assessments && assessments.length > 0) {
                        html += `
                            <table class="assessment-table">
                                <thead>
                                    <tr>
                                        <th>Subject</th>
                                        <th>Assessment Type</th>
                                        <th>Status</th>
                                        <th>Deadline</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                        `;

                        assessments.forEach(assessment => {
                            const deadline = new Date(assessment.deadline);
                            const today = new Date();
                            let statusClass = '';
                            let actionButton = '';

                            // Escape any special characters in the description for JavaScript
                            const safeDescription = assessment.description.replace(/'/g, "\\'");
                            
                            if (assessment.status === 'submitted') {
        statusClass = 'status-submitted';
        // Use the actual description from database
        actionButton = `<button onclick="confirmAssessment(${assessment.assessmentID}, '${safeDescription}')">Confirm</button>`;
    } else if (assessment.status === 'no submission') {
        statusClass = 'status-overdue';
        // Use the actual description from database
        actionButton = `<button onclick="sendReminder(${assessment.assessmentID}, '${teacher.name}', '${safeDescription}')">Send Reminder</button>`;
    }

                            html += `
                                <tr>
                                    <td>${assessment.subjectName}</td>
                                    <td>${assessment.assessmentType}</td>
                                    <td><span class="status-badge ${statusClass}">${assessment.status}</span></td>
                                    <td>${assessment.deadline}</td>
                                    <td>${actionButton}</td>
                                </tr>
                            `;
                        });

                        html += `
                                </tbody>
                            </table>
                        `;
                    } else {
                        html += `<p>No assessments found for this class.</p>`;
                    }

                    document.getElementById("classInfo").innerHTML = html;
                })
                .catch(error => {
                    console.error('Error:', error);
                    document.getElementById("classInfo").innerHTML = "Error loading data. Please try again.";
                });
        }

        function confirmAssessment(assessmentId, description) {
            alert(`Confirming assessment: ${description}`);
        }

        // Updated reminder function using the actual description
        function sendReminder(assessmentId, teacherName, description) {
            alert(`Sending reminder to ${teacherName} for assessment: ${description}`);
        }
    </script>
</body>
</html>
