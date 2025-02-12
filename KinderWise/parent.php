<?php
session_start();
include("connection.php");

$parent_name = $_SESSION['username']; 
$query = "SELECT name FROM student WHERE parentID = (SELECT parentID FROM parent WHERE name = ?)";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $parent_name);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parent Dashboard</title>
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
            align-items: center;
            flex-grow: 1; /* Ensures centering within content area */
            height: 80vh;
            
        }

        .dashboard-buttons a button {
            font-family: Trebuchet MS, sans-serif;
            padding: 60px 60px;
            margin: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            background-color: #557FF7;
            color: white;
            border: none;
            border-radius: 20px;
            transition: 0.3s;
        }

        .dashboard-buttons a button:hover {
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
                    <li><a href="parent.php">📊 Academic Report</a></li>
                    <li><a href="parent.php">⏱️ Assessment</a></li>
                    <li><a href="parent.php">📝 Announcement</a></li>
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
            <h1>Welcome, <?php echo htmlspecialchars($parent_name); ?></h1>
            <div class="dashboard-buttons">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $child_name = htmlspecialchars($row['name']);
                        echo "<a href='child.php?student_name=$child_name'><button>$child_name</button></a>";
                    }
                } else {
                    echo "<p>No children found.</p>";
                }
                ?>
            </div>
        </div>
    </div>
</body>

</html>
