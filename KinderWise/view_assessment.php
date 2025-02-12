<?php
session_start();
include("connection.php");

$student_name = $_GET['student_name']; 

$classQuery = "SELECT classCode FROM student WHERE name = ?";
$classStmt = $conn->prepare($classQuery);
$classStmt->bind_param("s", $student_name);
$classStmt->execute();
$classResult = $classStmt->get_result();
$studentClass = $classResult->fetch_assoc()['classCode'] ?? 'N/A';

// Get student's class and year
$studentQuery = "SELECT classCode, yearCode FROM student WHERE name = ?";
$studentStmt = $conn->prepare($studentQuery);
$studentStmt->bind_param("s", $student_name);
$studentStmt->execute();
$studentResult = $studentStmt->get_result();
$studentData = $studentResult->fetch_assoc();
$studentClass = $studentData['classCode'] ?? 'N/A';
$yearCode = $studentData['yearCode'] ?? 'N/A';

// Get available subjects for filtering
$subjectQuery = "SELECT DISTINCT subjectName FROM subject WHERE yearCode = ?";
$subjectStmt = $conn->prepare($subjectQuery);
$subjectStmt->bind_param("s", $yearCode);
$subjectStmt->execute();
$subjectResult = $subjectStmt->get_result();

// Get available semesters for filtering
$semesterQuery = "SELECT DISTINCT semesterCode FROM semester WHERE yearCode = ?";
$semesterStmt = $conn->prepare($semesterQuery);
$semesterStmt->bind_param("s", $yearCode);
$semesterStmt->execute();
$semesterResult = $semesterStmt->get_result();

// Handle filters
$selectedSubject = $_GET['subject'] ?? '';
$selectedSemester = $_GET['semester'] ?? '';

// Build assessment query based on filters
$assessmentQuery = "SELECT a.*, s.semesterCode 
                   FROM assessment a 
                   JOIN semester s ON a.semesterCode = s.semesterCode 
                   WHERE a.yearCode = ? AND a.status = 'submitted'";
$params = array($yearCode);
$types = "s";

if ($selectedSubject) {
    $assessmentQuery .= " AND a.subjectName = ?";
    $params[] = $selectedSubject;
    $types .= "s";
}
if ($selectedSemester) {
    $assessmentQuery .= " AND a.semesterCode = ?";
    $params[] = $selectedSemester;
    $types .= "s";
}

$assessmentQuery .= " ORDER BY a.postedOn DESC";
$assessmentStmt = $conn->prepare($assessmentQuery);

if (!empty($params)) {
    $assessmentStmt->bind_param($types, ...$params);
}
$assessmentStmt->execute();
$assessmentResult = $assessmentStmt->get_result();

// Check if there are any assessments
$hasAssessments = $assessmentResult->num_rows > 0;
$tableContent = '';
if ($hasAssessments) {
    while ($assessment = $assessmentResult->fetch_assoc()) {
        $tableContent .= '
            <tr>
                <td>' . htmlspecialchars($assessment['subjectName']) . '</td>
                <td>' . htmlspecialchars($assessment['semesterCode']) . '</td>
                <td>' . htmlspecialchars($assessment['assessmentType']) . '</td>
                <td>' . date('Y-m-d', strtotime($assessment['postedOn'])) . '</td>
                <td>
                    <a href="view_assessment_details.php?assessmentID=' . $assessment['assessmentID'] . '&student_name=' . urlencode($student_name) . '">
                        <button class="view-details-btn">View Details</button>
                    </a>
                </td>
            </tr>
        ';
    }
} else {
    $tableContent = '<tr>
        <td colspan="5" class="no-data">
            <div class="no-assessments">
                <p>📚 No assessments have been posted at the moment.</p>
                <p class="sub-message">Please check back later for updates.</p>
            </div>
        </td>
    </tr>';
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Assessment</title>
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
            padding: 20px 50px;
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

        .filter-section {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 25px;
        }

        .filter-section select {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-family: Trebuchet MS, sans-serif;
            min-width: 150px;
        }

        .reset-filter {
            padding: 8px 15px;
            background-color: #ff4444;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-family: Trebuchet MS, sans-serif;
        }

        .assessment-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }

        .assessment-table th {
            background-color: #f8f9fa;
            padding: 12px 15px;
            text-align: center;
            font-weight: 600;
            color: #555;
        }

        .assessment-table td {
            padding: 12px 15px;
            border-top: 1px solid #eee;
        }

        .view-details-btn {
            padding: 5px 10px;
            background-color: rgb(247, 158, 54);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-family: Trebuchet MS, sans-serif;
        }

        .view-details-btn:hover {
            background-color: rgb(222, 141, 47);
        }

        .no-assessments {
            text-align: center;
            padding: 40px;
            font-size: 18px;
            font-weight: 600;
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
            <div class="filter-section">
                <form method="GET" action="" id="filterForm">
                    <input type="hidden" name="student_name" value="<?php echo htmlspecialchars($student_name); ?>">
                    
                    <select name="subject" onchange="this.form.submit()">
                        <option value="">All Subjects</option>
                        <?php while ($subject = $subjectResult->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($subject['subjectName']); ?>"
                                    <?php echo ($selectedSubject == $subject['subjectName']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($subject['subjectName']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <select name="semester" onchange="this.form.submit()">
                        <option value="">All Semesters</option>
                        <?php while ($semester = $semesterResult->fetch_assoc()): ?>
                            <option value="<?php echo htmlspecialchars($semester['semesterCode']); ?>"
                                    <?php echo ($selectedSemester == $semester['semesterCode']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($semester['semesterCode']); ?>
                            </option>
                        <?php endwhile; ?>
                    </select>

                    <?php if ($selectedSubject || $selectedSemester): ?>
                        <button type="button" class="reset-filter" onclick="window.location.href='view_assessment.php?student_name=<?php echo urlencode($student_name); ?>'">
                            Reset Filter
                        </button>
                    <?php endif; ?>
                </form>
            </div>

            <table class="assessment-table">
                <thead>
                    <tr>
                        <th>Subject</th>
                        <th>Semester</th>
                        <th>Assessment</th>
                        <th>Posted On</th>
                        <th> </th>
                    </tr>
                </thead>
                <tbody>
                    <?php echo $tableContent?>
                </tbody>
            </table>
            
        </div>
    </div>
</body>

</html>
