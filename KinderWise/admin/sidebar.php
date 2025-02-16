<!-- admin side bar and nav bar -->
<?php
include("../connection.php");


?>

<!doctype html>

<head>
    <style>
        .sidebar {
            height: 100%;
            width: 300px;
            position: fixed;
            z-index: 1;
            top: 0;
            left: 0;
            background-color: white;
            overflow-x: hidden;
            padding: 20px 25px;
            display: flex;
            flex-direction: column;
        }

        .logo {
            text-align: left;
            margin-bottom: 20px;
        }

        header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            background: rgb(255, 255, 255);
            padding: 15px 30px;
            color: white;
            margin-left: 340px;
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

        .sidebar-users,
        .sidebar-settings {
            background-color: #E7EEFD;
            border-radius: 5px;
            margin: 10px 0;
        }

        .sidebar-h5 {
            background-color: #CBD8FC;
            color: black;
            padding: 10px 15px;
            margin: 0;
            border-radius: 5px 5px 0 0;
            font-family: Trebuchet MS, sans-serif;
            text-align: left;
        }

        .sidebar-table {
            width: 100%;
            border-collapse: collapse;
        }
        

        .sidebar-td {
            padding: 5px 10px 6px 10px;
            border-bottom: 1px solid #ccc;
            font-family: Trebuchet MS, sans-serif;
            text-align: left;
        }

        /* Remove border from last row in each table */
        .sidebar-table .sidebar-tr:last-child .sidebar-td {
            border-bottom: none;
        }

        .sidebar-actions {
            text-align: right;
            white-space: nowrap;
        }

        .sidebar-actions a,
        .sidebar-link {
            text-decoration: none;
            font-size: 13px;
            color: black;
            padding: 0 3px;
        }

        .sidebar-actions a:hover,
        .sidebar-link:hover {
            color: #557FF7;
        }

        .sidebar-hr {
            margin: 15px 0;
            border: 1px solid #557FF7;
        }

        .bottom-nav {
            margin-top: auto;
            font-family: Trebuchet MS, sans-serif;
            width: 300px;
            background: rgb(255, 255, 255);
            display: flex;
            flex-direction: column;
            text-decoration: none;
            height: 100vh;
            text-align: left;
        }

        .bottom-nav a {
            padding: 10px 0px;
            color: black;
            text-decoration: none;
            font-size: 16px;
        }
    </style>

</head>

<body>
    <header>
        <div class="top-right-buttons">
            <!-- <button>🔔 Notifications</button> -->
            <button>👤 Profile</button>
        </div>
    </header>
    <div class="sidebar">
        <div class="logo">
            <img src="../pics/logo.png" alt="KinderWise Logo" width="150px">
        </div>

        <div class="sidebar-users">
            <h5 class="sidebar-h5">USERS</h5>
            <table class="sidebar-table">
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewUser.php?role=administrator" class="sidebar-link">Administrator</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addUser.php?role=administrator">➕</a>
                        <a href="viewUser.php?role=administrator">✏️</a>
                        <a href="viewUser.php?role=administrator">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewUser.php?role=principal" class="sidebar-link">Principal</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addUser.php?role=principal">➕</a>
                        <a href="viewUser.php?role=principal">✏️</a>
                        <a href="viewUser.php?role=principal">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewUser.php?role=teacher" class="sidebar-link">Teacher</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addUser.php?role=teacher">➕</a>
                        <a href="viewUser.php?role=teacher">✏️</a>
                        <a href="viewUser.php?role=teacher">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewUser.php?role=parent" class="sidebar-link">Parent</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addUser.php?role=parent">➕</a>
                        <a href="viewUser.php?role=parent">✏️</a>
                        <a href="viewUser.php?role=parent">🗑️</a>
                    </td>
                </tr>
            </table>
        </div>

        <hr class="sidebar-hr">

        <div class="sidebar-settings">
            <h5 class="sidebar-h5">SYSTEM SETTINGS</h5>
            <table class="sidebar-table">
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=year" class="sidebar-link">Year</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=year">➕</a>
                        <a href="viewSetting.php?entity=year">✏️</a>
                        <a href="viewSetting.php?entity=year">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=semester" class="sidebar-link">Semester</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=semester">➕</a>
                        <a href="viewSetting.php?entity=semester">✏️</a>
                        <a href="viewSetting.php?entity=semester">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=subject" class="sidebar-link">Subject</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=subject">➕</a>
                        <a href="viewSetting.php?entity=subject">✏️</a>
                        <a href="viewSetting.php?entity=subject">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=class" class="sidebar-link">Class</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=class">➕</a>
                        <a href="viewSetting.php?entity=class">✏️</a>
                        <a href="viewSetting.php?entity=class">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=student" class="sidebar-link">Student</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=student">➕</a>
                        <a href="viewSetting.php?entity=student">✏️</a>
                        <a href="viewSetting.php?entity=student">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=assessment" class="sidebar-link">Assessment</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=assessment">➕</a>
                        <a href="viewSetting.php?entity=assessment">✏️</a>
                        <a href="viewSetting.php?entity=assessment">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=result" class="sidebar-link">Result</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=result">➕</a>
                        <a href="viewSetting.php?entity=result">✏️</a>
                        <a href="viewSetting.php?entity=result">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="viewSetting.php?entity=announcement" class="sidebar-link">Announcement</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="addSetting.php?entity=announcement">➕</a>
                        <a href="viewSetting.php?entity=announcement">✏️</a>
                        <a href="viewSetting.php?entity=announcement">🗑️</a>
                    </td>
                </tr>
            </table>
        </div>

        <hr class="sidebar-hr">

        <div class="bottom-nav">
            <li><a href="../logout.php">↩️ Logout</a></li>
        </div>
    </div>
</body>

</html>