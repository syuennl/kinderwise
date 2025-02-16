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

  .announcement{
    background-color: #f5f8fb;
  }

  .announcement-info{
    padding-left: 40px;
    display: flex;
    flex-direction: column;
    align-items: flex-start;
    width: fit-content;
  }

  .announcement-details{
    width: 600px;
    height: 200px;
    text-align: left;
    font-size: 14px;
    border: 1px solid rgb(198, 198, 198);
    padding: 10px;
    white-space:pre-wrap;
  }

  .buttons{
    align-self: flex-end;
    right: 380px;
  }

  .edit,
  .delete {
    padding: 5px 10px;
    border: none;
    cursor: pointer;
    margin-left: 10px;
  }

  .edit {
    background: #91a8d1;
    color: white;
    border: 1px solid #778dca;
    border-radius: 15px;
    width: 80px;
    height: 30px;
    font-weight: bold;
  }
  .delete {
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
    <title>Announcement</title>
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
                      <li><a href="manageAssessment.php">📝 Assessment</a></li>
                      <li><a href="uploadMarks.php">💯 Grading</a></li>
                      <li><a href="generateReport.php">📊 Performance Report</a></li>
                      <li class="selected-pg"><a href="manageAnnouncement.php">🗪 Announcement</a></li>
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
          <section class="announcement">
            <h1>Announcement</h1>
            <input type="text" class="class-name" value="1 GREEN" size="2" disabled>
            <br><br><br>
            
            <div class="announcement-info">
                <span>Title: <span data-field="title"></span></span> <br><br>
                <span>Announcement details:</span> <br>
                <p class="announcement-details" data-field="details"></p> 
                <div class="buttons">
                  <button class="edit">Edit</button>
                  <button class="delete" onclick="deleteAnnouncement()">Delete</button>
                </div>
            </div>
          </section>
        </div>
    </div>
  </body>

  <script>
    document.addEventListener('DOMContentLoaded', function(){
      loadAnnouncement(); // display announcement details

      // dashboard button
      document.querySelector('.dashboard').addEventListener('click', function(){
        location.href = "teacher.php";
      })
    });

    function getAnnouncementID()
    {
      const urlParams = new URLSearchParams(window.location.search);
      return urlParams.get('id'); // get the announcement id passed from url
    }

    function loadAnnouncement()
    {
      const id = getAnnouncementID(); // get the id passed
      if(!id){
        alert('No announcement ID provided');
        return;
      }

      const formData = new FormData();
      formData.append('action', 'view');
      formData.append('id', id);

      fetch("announcement.php?", {  
        method: 'POST',
        body: formData
      })
      .then(response => response.json()) // receive json response
      .then(announcement => {
        if(!announcement){
          alert('Announcement not found');
          return;
        }

        // assign the database values to the page attributes
        document.querySelector('[data-field="title"]').textContent = announcement.announcementTitle;
        document.querySelector('[data-field="details"]').textContent = announcement.details;
        
        // setup edit btn
        const editBtn = document.querySelector('.edit');
        editBtn.setAttribute('data-id', announcement.announcementID); // set data-id for edit btn
        editBtn.addEventListener('click', function(){
          const announcementID = this.getAttribute('data-id');
          location.href = "editAnnouncement.php?id=" + announcementID;
        })

        // setup delete btn
        const deleteBtn = document.querySelector('.delete');
        deleteBtn.setAttribute('data-id', announcement.announcementID); // set attribute for delete btn
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Error loading announcement');
      });
    }

    function deleteAnnouncement()
    {
      const id = getAnnouncementID();
      let confirmation = confirm("Are you sure you want to delete this?");
      if (confirmation) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);
        fetch('announcement.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(result => {
          if(result.success)
          {
            alert('Announcement deleted successfully');
            window.location.href = 'manageAnnouncement.php';
          }
          else
            alert('Failed to update announcement');
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Error saving announcement');
        });
      }
    }
  </script>
</html>