<?php
  session_start();
  include("connection.php");
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

  .assessment{
    background-color: #f5f8fb;
  }

  .assessment-info{
    padding-left: 40px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: fit-content;
  }

  .assessment-description{
    width: 600px;
    height: 200px;
  }

  .buttons{
    align-self: flex-end;
    right: 380px;
  }

  .save,
  .cancel {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
  }

  .save {
    background: #91a8d1;
    color: white;
    border: 1px solid #778dca;
    border-radius: 15px;
    width: 80px;
    height: 30px;
    font-weight: bold;
  }
  .cancel {
    background: #f5f8fb;
    color: #778dca;
    border:1px solid #778dca;
    border-radius: 15px;
    width: 80px;
    height: 30px;
    font-weight: bold;
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
                <a href="teacher.php"><button>Dashboard</button></a>
                
                <div class="divider"></div>
                
                <div class="section-title">PAGES</div>
                
            
                <div class="pages">
                      <li class="selected-pg"><a href="manageAssessment.php">📝 Assessment</a></li>
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

        <div class="main-content">
          <section class="assessment">
            <h1>Assessment</h1>
            <input type="text" class="class-name" value="1 GREEN" size="2" disabled>
            <br><br><br>
            
            <div class="assessment-info">
                <div>
                  <span>Subject: </span> 
                  <select name="editsubject" id="subject-id">
                    <option data-field="subject" selected hidden></option>
                    <option id="BM" ></option>
                    <option id="BI" ></option>
                    <option id="BC" ></option>
                    <option id="MT" ></option>
                    <option id="SC" ></option>
                  </select>
                </div>
                <div>
                  <span>Semester:</span> 
                  <select name="editsemester" id="semester-id">
                    <option data-field="semester" selected hidden></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select> 
                </div><br><br>
                <span>Assessment details:</span> <br><br>
                <textarea class="assessment-description" data-field="description"></textarea> 
                <br><br>

                <div class="buttons">
                  <button class="save">Save</button>
                  <button class="cancel">Cancel</button>
                </div>
            </div>
          </section>
        </div>
    </div>
  </body>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      loadAssessment(); // display assessment details

      // dashboard button
      document.querySelector('.dashboard').addEventListener('click', function(){
        location.href = "teacher.php";
      })
    });

    function getAssessmentID()
    {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('id'); // get the assessment id passed from url
    }

     function loadAssessment()
    {
      const id = getAssessmentID(); // get the id passed
      if(!id){
        alert('No assessment ID provided');
        return;
      }
 
      const formData = new FormData();
      formData.append('action', 'view');
      formData.append('id', id);
 
      fetch("assessment.php", {  
        method: 'POST',
        body: formData
      })
      .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
      })
      .then(assessment => {
        if(!assessment){
          alert('Assessment not found');
          return;
        }
 
        // assign the database values to the page attributes
        document.querySelector('[data-field="subject"]').textContent = assessment.subjectName;
        if(assessment.yearCode == 'Year1')
          year = 'Y1';
        else if(assessment.yearCode == 'Year2')
          year = 'Y2';
        else
          year = 'Y3';

        document.getElementById('BM').textContent = 'Bahasa Malaysia ' + year;
        document.getElementById('BI').textContent = 'English ' + year;
        document.getElementById('BC').textContent = 'Mandarin ' + year;
        document.getElementById('MT').textContent = 'Mathematics ' + year;
        document.getElementById('SC').textContent = 'Science ' + year;
 
        const semesterCode = parseInt(assessment.semesterCode.match(/\d/)[0]);  // trim the front part of sem code
        document.querySelector('[data-field="semester"]').textContent = semesterCode;
        document.querySelector('[data-field="description"]').value = assessment.description;
 
        // setup save btn
        const saveBtn = document.querySelector('.save');
        saveBtn.setAttribute('data-id', assessment.assessmentID); // set attribute for save btn
        saveBtn.addEventListener('click', saveAssessment)
 
        // setup cancel btn
        const cancelBtn = document.querySelector('.cancel');
        cancelBtn.setAttribute('data-id', assessment.assessmentID); // set attribute for cancel btn
        cancelBtn.addEventListener('click', function(){
          const assessmentID = this.getAttribute('data-id');
          location.href = "viewAssessment.php?id=" + assessmentID;
        })
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error loading assessment');
      });
    }

    function saveAssessment()
    {
      const id = this.getAttribute('data-id');
      const subject = document.getElementById('subject-id').value;
      const semester = document.getElementById('semester-id').value;
      const description = document.querySelector('[data-field="description"]').value;
 
      const formData = new FormData();
      formData.append('action', 'edit');
      formData.append('id', id);
      formData.append('subject', subject);
      formData.append('semester', semester);
      formData.append('description', description);
 
      fetch('assessment.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(result => {
        if(result.success)
        {
          alert('Assessment updated successfully');
          window.location.href = 'viewAssessment.php?id=' + id;
        }
        else
          alert('Failed to update assessment');
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error saving assessment');
      });
    }
  </script>
</html>
