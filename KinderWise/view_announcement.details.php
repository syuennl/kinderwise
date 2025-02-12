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
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Announcement</title>
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
                    <li><a href="view_assessment.php?student_name=<?php echo urlencode($student_name); ?>">⏱️ Assessment</a></li>
                    <li><a href="view_announcement.php?student_name=<?php echo urlencode($student_name); ?>"><div id="currentPage">📝 Announcement</a></li>
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
            <h1>Announcement</h1>
            <div class="student-info">
                <h3 class="student-name"><?php echo $student_name; ?></h3>
                <span class="student-class"><?php echo htmlspecialchars($studentClass); ?></span>
            </div>
        </div>
    </div>
</body>

</html>
