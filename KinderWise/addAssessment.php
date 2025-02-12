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

  .add,
  .cancel {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
  }

  .add {
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
                <a href="teacher.php" class="dashboard"><button>Dashboard</button></a>
                
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
          <section class="assessment">
            <h1>Assessment</h1>
            <input type="text" class="class-name" value="1 RED" size="2" disabled>
            <br><br><br>
            
            <div class="assessment-info">
                <div>
                  <span>Subject: </span> <input type="text" class="subject"><br><br> <!--generalise the class names***-->
                </div>
                
                <div>
                  <span>Semester:</span> 
                  <select name="editsemester" id="semester-id">
                    <option selected hidden></option>
                    <option value="1">1</option>
                    <option value="2">2</option>
                  </select>             
                </div><br><br>
                <span>Assessment details:</span> <br><br>
                <textarea class="assessment-description"></textarea> 
                <br><br>

                <div class="buttons">
                  <button class="add">Add</button>
                  <button class="cancel">Cancel</button>
                </div>
            </div>
          </section>
        </div>
    </div>
  </body>

  <script>
    document.addEventListener('DOMContentLoaded', function(){

        // dashboard button
        document.querySelector('.dashboard').addEventListener('click', function(){
        location.href = "teacher.php";
        })

        // setup add and cancel btn
        document.querySelector('.add').addEventListener('click', addAssessment);
        document.querySelector('.cancel').addEventListener('click', function(){
            location.href = "manageAssessment.php";
        });
    });

    function getAssessmentID()
    {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('id'); // get the assessment id passed from url
    }

    // function saveAssessment()
    // {
    //   const semester = document.getElementById('semester-id').value;
    //   const description = document.querySelector('[data-field="description"]').value;

    //   const formData = new FormData();
    //   formData.append('action', 'edit');
    //   formData.append('semester', semester);
    //   formData.append('description', description);

    //   fetch('assessment.php', {
    //     method: 'POST',
    //     body: formData
    //   })
    //   .then(response => response.json())
    //   .then(result => {
    //     if(result.success)
    //     {
    //       alert('Assessment updated successfully');
    //       window.location.href = 'manageAssessment.php';
    //     }
    //     else
    //       alert('Failed to update assessment');
    //   })
    //   .catch(error => {
    //     console.error('Error:', error);
    //     alert('Error saving assessment');
    //   });
    // }

    function addAssessment()
    {
        const subject = document.querySelector('.subject').value;
        const semester = document.getElementById('semester-id').value;
        const description = document.querySelector('.assessment-description').value;

        // validate input
        if (!subject || !semester || !description) {
          alert('Please fill in all fields');
          return;
        }
        
        const formData = new FormData();
        formData.append('action', 'add');
        formData.append('subject', subject);
        formData.append('semester', semester);
        formData.append('description', description);

        fetch('assessment.php?', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if(result.success)
            {
                alert('Assessment added successfully');
                window.location.href = 'manageAssessment.php';
            }
            else
                alert('Failed to add assessment');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding assessment');
        });
    }
  </script>
</html>