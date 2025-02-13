<?php
session_start();
include("connection.php");

$student_name = $_GET['student_name'];
$assessment_id = $_GET['assessmentID']; 

$classQuery = "SELECT classCode FROM student WHERE name = ?";
$classStmt = $conn->prepare($classQuery);
$classStmt->bind_param("s", $student_name);
$classStmt->execute();
$classResult = $classStmt->get_result();
$studentClass = $classResult->fetch_assoc()['classCode'] ?? 'N/A';

$assessmentQuery = "SELECT subjectName, semesterCode, description FROM assessment WHERE assessmentID = ?";
$assessmentStmt = $conn->prepare($assessmentQuery);
$assessmentStmt->bind_param("i", $assessment_id);
$assessmentStmt->execute();
$assessmentResult = $assessmentStmt->get_result();
$assessment = $assessmentResult->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assessment Details</title>
    <style>
        body {
            font-family: Trebuchet MS, sans-serif;
            text-align: center;
            margin: 0;
            background-color: #f4f4f4;
            overflow-x : hidden;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background:rgb(255, 255, 255);
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
            background:rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            height: 100vh;
        }

        .navbar li {
            height: 40px;
            display:flex;
            flex-direction: column;
            justify-content: center;
            align-items : flex-start;
            padding: 0px 15px;
            margin: 10px 10px;
        }

        .navbar li a {
            color:#202224;
        }

        .navbar li:hover {
            box-shadow: inset 600px  0 0 0 rgb(185, 204, 255);
            cursor: pointer;
            transition: ease-out 0.9s ;
        }
        
        /*
        .navbar li a:hover {
            color:rgb(255, 255, 255);
        }
        */

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
            background-color:rgb(185, 204, 255);
            padding: 10px;
        }

        .divider {
            height: 1px;
            background:rgb(213, 213, 213);
            margin: 10px 15px;
        }

        .section-title {
            color:rgb(198, 198, 198);
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
            display:flex;
            flex-direction: column;
            align-items: flex-start;
            flex-grow: 1; /* Ensures centering within content area */
            height: auto;
            margin-left: 50px;          
        }

        .student-info {
            display: flex;
            align-items: center;
            margin-top: -25px;
            margin-bottom: 20px;
        }

        .student-name {
            font-size: 15px;
            font-weight: 600;
            margin-right: 15px;
            background-color: rgb(212, 223, 255);
            color: rgb(90, 85, 247);
            padding: 5px 8px;
            border-radius: 5px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .student-class {
            background-color: rgb(233, 213, 255);
            color: rgb(126, 34, 206);
            font-size: 15px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .assessment-details {
            background: white;
            padding: 30px;
            border-radius: 10px;
            width: 100%;
            max-width: 800px;
            text-align: left;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
        }

        .detail-row {
            flex-direction: column;
            margin-bottom: 25px;
        }

        .detail-label {
            margin-right: 20px;
            margin-bottom: 10px;
            font-weight: bold;
            color: #666;
            font-size: 18px;
        }

        .detail-content {
            background-color:rgb(245, 248, 255);
            padding: 15px;
            border-radius: 5px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            display: inline-block; 
        }

        .back-button {
            background-color: #557FF7;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 30px;
            font-family: Trebuchet MS, sans-serif;
        }

        .back-button:hover {
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
            <button>👤 Profile</button>
        </div>
    </header>
    
    <div class = "content">
        <div class="navbar">
            <nav>
                <a href="parent.php"><button>Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                    <li><a href="academic_report.php?student_name=<?php echo urlencode($student_name); ?>">📊 Academic Report</a></li>
                    <li><a href="view_assessment.php?student_name=<?php echo urlencode($student_name); ?>"><div id="currentPage">⏱️ Assessment</a></li>
                    <li><a href="view_announcement.php?student_name=<?php echo urlencode($student_name); ?>">📝 Announcement</a></li>
                    <br></br>
                    <br></br>
                    
                </div>

                <div class="divider"></div>
                
                <div class="bottom">
                    <li><a href="logout.php">↩️ Logout</a></li>
                </div>
    
            </nav>
        </div>

        <div class="container">
            <h1>Assessment</h1>
            <div class="student-info">
                <h3 class="student-name"><?php echo $student_name; ?></h3>
                <span class="student-class"><?php echo htmlspecialchars($studentClass); ?></span>
            </div> 
            
            <div class="assessment-details">
                <div class="detail-row">
                    <div class="detail-label">Subject:</div>
                    <div class="detail-content"><?php echo htmlspecialchars($assessment['subjectName']); ?></div>
                </div>
                
                <div class="detail-row">
                    <div class="detail-label">Semester:</div>
                    <div class="detail-content"><?php echo htmlspecialchars($assessment['semesterCode']); ?></div>
                </div>

                <div class="detail-label">Assessment details:</div>
                <div class="detail-content"><?php echo nl2br(htmlspecialchars($assessment['description'])); ?></div>
            </div>

            <button class="back-button" onclick="window.location.href='view_assessment.php?student_name=<?php echo urlencode($student_name); ?>'">← Back</button>

        </div>
    </div>
</body>

</html>
