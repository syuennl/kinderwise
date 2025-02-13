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

$query = "SELECT a.subjectName, r.finalScore, r.status, a.assessmentType FROM result r JOIN assessment a ON r.assessmentID = a.assessmentID WHERE r.studentID = (SELECT studentID FROM student WHERE name = ?) ORDER BY a.subjectName";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $student_name);
$stmt->execute();
$result = $stmt->get_result();

$hasResults = $result->num_rows > 0;

$marks = [
    'Midterm' => [],
    'Finals' => []
];

$totalMarksMidterm = 0;
$totalMarksFinals = 0;
$verifiedCountMidterm = 0;
$verifiedCountFinals = 0;

$subjects = [];
$result_subjects = [];

if ($hasResults) {
    $result_subjects = $result->fetch_all(MYSQLI_ASSOC);
    foreach ($result_subjects as $row) {
        if (!in_array($row['subjectName'], $subjects)) {
            $subjects[] = $row['subjectName'];
        }
    }

    $result->data_seek(0);

    foreach ($result_subjects as $row) {
        $subjectName = $row['subjectName'];
        $finalScore = $row['finalScore'];
        $status = $row['status'];
        $assessmentType = $row['assessmentType'];

        // Check if the result is verified
        if ($status === 'verified') {
            $marks[$assessmentType][$subjectName] = $finalScore;
            if ($assessmentType === 'Midterm') {
                $totalMarksMidterm += $finalScore;
                $verifiedCountMidterm++;
            } else if ($assessmentType === 'Finals') {
                $totalMarksFinals += $finalScore;
                $verifiedCountFinals++;
            }
        } else {
            $marks[$assessmentType][$subjectName] = '-'; // Replace with '-' if not verified
        }
    }
}

// Calculate averages
$averageMidterm = ($verifiedCountMidterm > 0) ? ($totalMarksMidterm / $verifiedCountMidterm) : '-';
$averageFinals = ($verifiedCountFinals > 0) ? ($totalMarksFinals / $verifiedCountFinals) : '-';

// Prepare the table rows
$tableContent = '';
if ($hasResults && !empty($subjects)) {
    foreach ($subjects as $index => $subject) {
        $midtermMark = isset($marks['Midterm'][$subject]) ? $marks['Midterm'][$subject] : '-';
        $finalsMark = isset($marks['Finals'][$subject]) ? $marks['Finals'][$subject] : '-';
        $tableContent .= "
            <tr>
                <td class='text-center'>" . str_pad($index + 1, 2, '0', STR_PAD_LEFT) . ".</td>
                <td class='text-left'>{$subject}</td>
                <td class='text-center'>{$midtermMark}</td>
                <td class='text-center'>{$finalsMark}</td>
            </tr>
        ";
    }
} else {
    $tableContent = "
        <tr>
            <td colspan='4' class='no-data'>
                <div class='no-data-message'>
                    <p>📚 No academic report available at the moment.</p>
                    <p class='sub-message'>Please check back later for updates.</p>
                </div>
            </td>
        </tr>
    ";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academic Report</title>
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
            justify-content: center;
            align-items: flex-start;
            flex-grow: 1; /* Ensures centering within content area */
            height: 80vh;
            margin-left: 50px;
            margin-top: 28px;
        }

        .table-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1);
            width: 90%;
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            font-size: 18px;

        }

        table {
            min-width: 100%;
            height: 85%;
            background-color: white;
            border-collapse: collapse;
        }

        th {
            padding: 8px 15px;  
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
            color: #3b5fc9; 
            text-align: left;
        }

        td {
            padding: 15px 15px;
            border-bottom: 1px solid #e5e7eb;
            border-right: 1px solid #e5e7eb;
        }

        td:last-child, 
        th:last-child {
            border-right: none;
        }

        .text-center {
            text-align: center;
        }

        .text-left {
            text-align: left;
        }

        .average-row {
            margin-top: 15px;
            background-color: rgb(247, 158, 54);
            border-radius: 8px;
            display: flex;
            color: white;
            font-weight: 600;
            padding: 8px 5px;
            width: 100%;
            height: 10%;
        }

        .average-label {
            padding: 0 220px;
        }

        .average-value {
            border-left: 1px solid white;
            padding: 0 95px;
            text-align: center;
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
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="./pics/logo.png" alt="KinderWise Logo">
        </div>
        <div class="top-right-buttons">
            <button onclick="window.location.href='profile.php'">👤 Profile</button>
        </div>
    </header>
    
    <div class = "content">
        <div class="navbar">
            <nav>
                <a href="parent.php"><button>Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                    <li><a href="academic_report.php?student_name=<?php echo urlencode($student_name); ?>"><div id="currentPage">📊 Academic Report</a></li>
                    <li><a href="view_assessment.php?student_name=<?php echo urlencode($student_name); ?>">⏱️ Assessment</a></li>
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
            <h1>Academic Report</h1>
            <div class="student-info">
                <h3 class="student-name"><?php echo $student_name; ?></h3>
                <span class="student-class"><?php echo htmlspecialchars($studentClass); ?></span>
            </div>

            <div class="table-container <?php echo (!$hasResults) ? 'no-data-state' : ''; ?>">
                <table class="table">
                    <thead>
                        <tr>
                            <th class="text-center">No.</th>
                            <th class="text-left">Subject</th>
                            <th class="text-center">Midterm</th>
                            <th class="text-center">Finals</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php echo $tableContent; ?>
                    </tbody>
                </table>

                <?php if ($hasResults && !empty($subjects)): ?>
                <div class="average-row">
                    <div class="average-label">Average</div>
                    <div class="average-value"><?php echo $averageMidterm; ?></div>
                    <div class="average-value"><?php echo $averageFinals; ?></div>
                </div>
                 <?php endif; ?>
            </div>
        </div>
    </div>
</body>

</html>
