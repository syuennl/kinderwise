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
    width: 50px;
    height: 25px;
    float: left;
    text-align: center;
    font-weight: bold;
    border-radius: 5px;
    border: 0;
    background-color: #f2d5ff;
    color: #dd46ff;
    box-shadow: 2px 5px 4px rgba(84, 82, 82, 0.2);
  }

  /*filters*/
  .filters {
    /*?*/
    display: flex;
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
  .subject-filter,
  .semester-filter,
  .reset {
    font-size: 14px;
  }

  .subject-filter,
  .semester-filter,
  .reset {
    border: 0;
    background-color: #f5f8fb;
  }

  .reset {
    cursor: pointer;
    color: #d52753;
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

  #green{
    color:rgb(49, 195, 115);
  }

  #red{
    color:rgb(246, 32, 86);
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

  .add {
    background: #ffbc38;
    color: white;
    border: none;
    border-radius: 15px;
    box-shadow: 2px 5px 4px rgba(84, 82, 82, 0.2);
    width: 70px;
    height: 30px;
    font-size: 24px;
    font-weight: bold;
    cursor: pointer;
    margin-left: 50px;
    margin-top: 20px;
    margin-bottom: 20px;
    float: left;
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
                <a href="teacher.php"><button class="dashboard">Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                      <li class="selected-pg"><a href="manageAssessment.php">Assessment</a></li>
                      <li><a href="uploadMarks.php">Grading</a></li>
                      <li><a href="">Performance Report</a></li>
                      <li><a href="manageAnnouncement.php">Announcement</a></li>
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

        <div class="main-content">
        <section>
          <h1>Assessment</h1>
          <input type="text" class="class-name" value="1 RED" size="2" disabled />
          <!--***tchr's class-->
          <br /><br />

          <!-- filters -->
          <div class="filters">
            <form action="assessment.php" method="POST" id="filter-form">
              <table class="filter-table">
                <tbody>
                  <tr>
                    <td class="filter-tag">Filter By:</td>

                    <td>
                      <select class="subject-filter" name="subject">
                        <option selected>All</option>
                        <option>Bahasa Malaysia</option>
                        <option>Mandarin</option>
                        <option>English</option>
                        <option>Science</option>
                        <option>Mathematics</option>
                      </select>
                    </td>

                    <td>
                      <select class="semester-filter" name="semester">
                        <option selected>All</option>
                        <option>1</option>
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
          </div>

          <!-- assessment table -->
          <table>
            <thead>
              <tr>
                <th>Subject</th>
                <th>Semester</th>
                <th>Assessment</th>
                <th>Posted on</th>
                <th></th>
              </tr>
            </thead>

            <tbody id="assessment-list">
              <!--loaded using script-->
            </tbody>
          </table>
          <button class="add">+</button> 
        </section>
      </div>
    </div>

    
  </body>

  <script>
    // when document loaded
    document.addEventListener("DOMContentLoaded", function () {
      filterAssessments(); // load assessments

      // filters
      document.getElementById("filter-form").addEventListener("submit", filterAssessments);
  
      document.querySelector(".subject-filter").addEventListener("change", filterAssessments); // subject
      document.querySelector(".semester-filter").addEventListener("change", filterAssessments); // semester

      // reset button
      document.querySelector('input[type="reset"]').addEventListener("click", function () {
          setTimeout(filterAssessments, 0); // run after form reset completes
      });

      // add button
      document.querySelector(".add").addEventListener("click", function () {
        location.href = "addAssessment.php";
      });

      // dashboard button
      document.querySelector(".dashboard").addEventListener("click", function () {
          location.href = "teacher.php";
      });
    });

    // called when filter is selected
    function filterAssessments() {
      if (event) event.preventDefault(); // prevent form submission if called from an event

      var form = document.getElementById("filter-form"); // get the form element
      var formData = new FormData(form); // get form data
      formData.append("action", "filter"); // specify which function to call

      // fetch the php file and pass the form data
      fetch("assessment.php", {
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
        document.getElementById("assessment-list").innerHTML = data; // update assessment table in html

        // setup edit buttons
        const editButtons = document.querySelectorAll(".edit");
        editButtons.forEach((button) => {
          button.addEventListener("click", function () {
            // reattach event listeners to edit buttons
            const assessmentID = this.getAttribute("data-id");
            location.href = "editAssessment.php?id=" + assessmentID;
          });
        });

        // setup assessment buttons
        const assessmentBtns = document.querySelectorAll(".subject-btn");
        assessmentBtns.forEach((button) => {
          button.addEventListener("click", function () {
            // reattach event listeners to subject buttons
            const assessmentID = this.getAttribute("data-id");
            location.href = "viewAssessment.php?id=" + assessmentID;
          });
        });

        // setup delete buttons
        const deleteBtns = document.querySelectorAll(".delete");
        deleteBtns.forEach((button) => {
          // reattach event listeners to delete buttons
          button.addEventListener("click", deleteAssessment);
        });
      })
      .catch((error) => {
        console.error("Error:", error);
      });

      return false; // prevent form submission
    }

    function deleteAssessment() {
      const id = this.getAttribute("data-id");
      let confirmation = confirm("Are you sure you want to delete this?");
      if (confirmation) {
        const formData = new FormData();
        formData.append("action", "delete");
        formData.append("id", id);
        fetch("assessment.php", {
          method: "POST",
          body: formData,
        })
        .then((response) => response.json())
        .then(result => {
          if (result.success) {
            alert("Assessment deleted successfully");
            window.location.href = "manageAssessment.php";
          } else alert("Failed to update assessment");
        })
        .catch((error) => {
          console.error("Error:", error);
          alert("Error deleting assessment");
        });
      }
    }
  </script>
</html>
