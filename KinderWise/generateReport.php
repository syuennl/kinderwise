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

  .menu { /**?? */
      display: flex;
      flex-direction: column;
      flex-grow: 1;
  }

  .pages {
      display: flex;
      flex-direction: column;
  }

  .selected-pg {
    background-color: #dee8ff;
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
  
  .main-content {
    flex-grow: 1;
    width: 100%;
    min-height: 100vh;
    padding-left: 40;
    padding-right: 40;
    padding-top: 10;
    background-color: #f5f8fb;
  }

  h1 {
    font-size: 28px;
    text-align: left;
  }

  .class-name {
    width: 70px;
    height: 25px;
    float: left;
    text-align: center;
    font-weight: bold;
    border-radius: 5px;
    border: 0;
    background-color: #A3D1C6;
    color: #3D8D7A;
    box-shadow: 2px 5px 4px rgba(84, 82, 82, 0.2);
  }

  /*report section*/
  .report-section{
    display: flex;
    justify-content: center;
    gap: 40px;
    padding: 20px;
  }

  .report1, .report2 {
    background: white;
    border-radius: 15px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    width: 400px;
  }

  .sem1-label, .sem2-label {
    width: 100%;
    margin-bottom: 20px;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 20px;
    text-align: center;
    font-size: 14px;
    font-weight: bold;
    padding: 8px 16px;
    background: #a4b4ff;
    color: white;
}

.report1 table, .report2 table { /**can remove? */
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
}

.report1-table th, .report2-table th {
    font-size: 13px;
    padding:20px;
}

.report1 tbody td, .report2 tbody td {
    padding: 15px 10px;
    text-align: center;
    font-size: 14px;
    color: #333;
}

  .subject-btn {
    font-size: 16;
    background-color: #f5f8fb;
    border: 0;
    cursor: pointer;
  }

  .edit,
  .delete {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
  }
  .edit {
    background: white;
    color: #778dca;
    border: 1px solid #778dca;
    border-radius: 15px;
    width: 80px;
    height: 30px;
    font-weight: bold;
  }

  .delete {
    background: #91a8d1;
    color: white;
    border: 1px solid #778dca;
    border-radius: 15px;
    width: 80px;
    height: 30px;
    font-weight: bold;
  }

  .grade-input{
    width: 35px;
    text-align: center;
  }

  .save {
    background: #5084fc;
    color: white;
    border: none;
    border-radius: 10px;
    box-shadow: 2px 5px 4px rgba(84, 82, 82, 0.2);
    width: 100px;
    height: 30px;
    font-size: 14px;
    font-weight: bold;
    cursor: pointer;
    margin-left: 50px;
    margin-right: 40px;
    margin-top: 40px;
    margin-bottom: 20px;
    float: right;
  }  
</style>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Performance Report</title>
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
                <a href="teacher.php"><button class="dashboard">Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                      <li><a href="manageAssessment.php">📝 Assessment</a></li>
                      <li><a href="uploadMarks.php">💯 Grading</a></li>
                      <li class="selected-pg"><a href="generateReport.php">📊 Performance Report</a></li>
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

        <div class="main-content">
        <section>
            <h1>Performance Report</h1>
            <input type="text" class="class-name" value="1 GREEN" size="2" disabled />
            <!--***tchr's class-->
            <br /><br />

            <div class="report-section">
                <!-- sem 1 report -->
                <div class="report1">
                    <table class="sem1-label">
                        <thead>
                        <tr>
                            <th>SEM 1</th>
                            <th>Midterm</th>
                        </tr>
                        </thead>
                    </table>

                    <table class="report1-table">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>AVERAGE MARKS</th>
                            </tr>
                        </thead>

                        <tbody id="report1-body">
                            <!--loaded from script-->
                        </tbody>
                    </table>
                </div>

                <!-- sem 2 report -->
                <div class="report2">
                    <table class="sem2-label">
                        <thead>
                        <tr>
                            <th>SEM 2</th>
                            <th>Finals</th>
                        </tr>
                        </thead>
                    </table>

                    <table class="report2-table">
                        <thead>
                            <tr>
                                <th>SUBJECT</th>
                                <th>AVERAGE MARKS</th>
                            </tr>
                        </thead>

                        <tbody id="report2-body">
                            <!--loaded from script-->
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
      </div>
    </div>

    
  </body>

  <script>
    // when document loaded
    document.addEventListener("DOMContentLoaded", function () {
      loadReport(); // load grading

      // dashboard button
      document.querySelector(".dashboard").addEventListener("click", function () {
          location.href = "teacher.php";
      });
    });

    // called when filter is selected
    function loadReport() {
      fetch('report.php')
      .then(response => response.json())
      .then(data => {
        let report1Html = '';
        for(const [subject, total] of Object.entries(data.semester1)) {
          report1Html += `
            <tr>
              <td>${subject}</td>
              <td>${total}</td>
            </tr>`;
        }
        document.getElementById('report1-body').innerHTML = report1Html;

        let report2Html = '';
        for(const [subject, total] of Object.entries(data.semester2)) {
          report2Html += `
            <tr>
              <td>${subject}</td>
              <td>${total}</td>
            </tr>`;
        }
        document.getElementById('report2-body').innerHTML = report2Html;
      })
      .catch(error => {
        console.error('Error loading report data:', error);
        document.getElementById('report1-body').innerHTML = '<tr><td colspan="2">Error loading data</td></tr>';
        document.getElementById('report2-body').innerHTML = '<tr><td colspan="2">Error loading data</td></tr>';
      });
    }

  </script>
</html>
