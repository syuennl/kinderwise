<?php
  session_start();
  include("connection.php");
  if(!isset($_SESSION['teacherID'])) {
    header('Location: login.php');
    exit();
  }
?>

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
    height: auto;
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
    padding: 45px 25px;
    margin: 10px;
    font-size: 16px;
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

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Assessment</title>
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
                  <a href="principal.php"><button>Dashboard</button></a>
                  
                  <div class="divider"></div>
                  
                  <div class="section-title">PAGES</div>
                  
              
                  <div class="pages">
                      <li><a href="manageAssessment.php">📝 Assessment</a></li>
                      <li><a href="uploadMarks.php">💯 Grading</a></li>
                      <li><a href="generateReport.php">📊 Performance Report</a></li>
                      <li><a href="manageAnnouncement.php">🗪 Announcement</a></li>
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
            <h1>Welcome, Teacher !</h1>
            <div class="dashboard-buttons">
                <a href="manageAssessment.php"><button>📝 Assessment</button></a>
                <a href="uploadMarks.php"><button>💯 Grading</button></a>
                <a href="generateReport.php"><button>📊 Performance Report</button></a>
                <a href="manageAnnouncement.php"><button>🗪 Announcement</button></a>
            </div>
          </div>
    </div>
  </body>

  <script>
    // add event listeners for:
    document.addEventListener('DOMContentLoaded', function() {
      
      // reset button 
      document.querySelector('input[type="reset"]').addEventListener('click', function() {
        setTimeout(filterAssessments, 0); // run after form reset completes
      });
    });

  </script>
</html>
