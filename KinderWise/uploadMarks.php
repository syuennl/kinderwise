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

  /*filters*/
  .filters {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 10px;
    margin-top: 10px;
  }

  .filter-table {
    width: 100%;
    border: 1.5px solid #ddd;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden;
  }

  .filter-table td {
    border-right: 1.5px solid #ddd;
    padding: 20px;
  }

  .filter-table td:last-child {
    border-right: 0;
  }

  .filter-tag,
  .semester-filter,
  .reset {
    font-size: 14px;
  }

  .semester-filter,
  .reset {
    border: 0;
    background-color: #f5f8fb;
  }

  .reset {
    cursor: pointer;
    color: #d52753;
  }

  .report{
    margin-right: 10px;
    background-color: #ffbc38;
    color: white;
    border: none;
    border-radius: 10px;
    box-shadow: 2px 5px 4px rgba(84, 82, 82, 0.2);
    padding: 10px;
    cursor: pointer;
    font-weight: bold;
  }

  /*assessment table*/
  table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0 10px;
  }

  thead {
    border-bottom: 1px solid #91a8d1;
  }

  th,
  td {
    padding: 20px;
    text-align: center;
  }

  th {
    color: #778dca;
  }

  .assessment-row {
    background-color: #f5f8fb;
    border-radius: 15px;
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
    <title>Grading</title>
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
                      <li class="selected-pg"><a href="uploadMarks.php">💯 Grading</a></li>
                      <li><a href="generateReport.php" >📊 Performance Report</a></li>
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
          <h1>Grading</h1>
          <input type="text" class="class-name" value="1 GREEN" size="2" disabled />
          <!--***tchr's class-->
          <br /><br />

          <!-- filters -->
          <div class="filters">
            <form action="grading.php" method="POST" id="filter-form">
              <table class="filter-table">
                <tbody>
                  <tr>
                    <td class="filter-tag">Filter By:</td>

                    <td>
                      <select class="semester-filter" name="semester">
                        <option selected>1</option>
                        <option>2</option>
                      </select>
                    </td>

                    <td>
                      <input class="reset" type="reset" value="Reset Filter" />
                    </td>
                  </tr>
                </tbody>
              </table>
            </form>
            <button class="report">Generate Performance Report</button>
          </div>

          <!-- grading table -->
          <table>
            <thead>
              <tr>
                <th>No.</th>
                <th>Name</th>
                <th>Bahasa Malaysia</th>
                <th>English</th>
                <th>Mandarin</th>
                <th>Mathematics</th>
                <th>Science</th>
              </tr>
            </thead>

            <tbody id="name-list">
                <!--loaded from script-->
            </tbody>
          </table>
          <button class="save">Save</button> 
        </section>
      </div>
    </div>

    
  </body>

  <script>
    // when document loaded
    document.addEventListener("DOMContentLoaded", function () {
      filterGrades(); // load grading

      // filters
      document.getElementById("filter-form").addEventListener("submit", filterGrades);
  
      document.querySelector(".semester-filter").addEventListener("change", filterGrades); // semester

      // reset button
      document.querySelector('input[type="reset"]').addEventListener("click", function () {
          setTimeout(filterGrades, 0); // run after form reset completes
      });

      // save button
      document.querySelector(".save").addEventListener("click", saveGrades);
      
      // report button
      document.querySelector(".report").addEventListener("click", function () {
          location.href = "generateReport.php";
      });

      // dashboard button
      document.querySelector(".dashboard").addEventListener("click", function () {
          location.href = "teacher.php";
      });
    });

    // called when filter is selected
    function filterGrades() {
      if (event) event.preventDefault(); // prevent form submission if called from an event

      var form = document.getElementById("filter-form"); // get the form element
      var formData = new FormData(form); // get form data
      formData.append("action", "filter"); // specify which function to call

      // fetch the php file and pass the form data
      fetch("grading.php", {
        method: "POST",
        body: formData,
      })
      .then((response) => {
        console.log("Response status:", response.status);
        if (!response.ok) {
          throw new Error("Network response was not ok");
        }
        return response.text();
      })
      .then((data) => {     
        // data = response in text from previous lines
        document.getElementById("name-list").innerHTML = data; // update grading table in html
      })
      .catch((error) => {
        console.error("Error:", error);
      });

      return false; // prevent form submission
    }

    function saveGrades()
    {
      // get all parameters
      const idElements = document.querySelectorAll('.studentId');
      const subjectScores = document.querySelectorAll('.grade-input');
      const studentIDs = [];
      const grades = {};
      const semester = document.querySelector('.semester-filter').value;
 
 
      idElements.forEach((idElement, index) => {
        const studid = idElement.value.trim();
          if (!studid) {
              console.error('Student ID element not found');
              return;
          }
          studentIDs.push(studid);
          grades[studid] = {};
          const subjectMappings = {
              'BM': 'Bahasa Malaysia',
              'EN': 'English',
              'MD': 'Mandarin',
              'MT': 'Mathematics',
              'SC': 'Science'
          };
 
 
          // Calculate the starting index for this student's scores
          const startIndex = index * 5; // Each student has 5 subjects
 
          // Process 5 subject scores for this student
          Object.entries(subjectMappings).forEach(([shortName, fullName], subjectIndex) => {
              const scoreInput = subjectScores[startIndex + subjectIndex];
              if (scoreInput) {
                  grades[studid][fullName] = scoreInput.value.trim();
              } else {
                  console.warn(`Input for ${fullName} not found for student ${studid}`);
                  grades[studid][fullName] = '-';
              }
          });
      });
 
      // validate whether we have data to send
      if (studentIDs.length === 0) {
        alert('No student data found to save');
        return;
      }
 
      console.log(grades);
 
      const formData = new FormData();
      formData.append('action', 'save');
      formData.append('semester', semester);
      formData.append('studentIDs', JSON.stringify(studentIDs));
      formData.append('grades', JSON.stringify(grades));
 
      // Debug log
      console.log('Sending data:', {
          action: 'save',
          semester: semester,
          studentIDs: studentIDs,
          grades: grades
      });
 
      // send form to backend to save
      fetch('grading.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if(result.success)
          alert('Grades saved successfully');
        else
          alert('Error saving grades: ' + result.error);
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error saving grades. Please try again.');
      });
    }

  </script>
</html>
