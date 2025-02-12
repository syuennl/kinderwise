<!-- admin side bar and nav bar -->
<?php
include("../connection.php");

function checkCount($type)
{
    global $conn;

    // Count the records
    $sql = "SELECT COUNT(*) as count FROM $type";
    $result = mysqli_query($conn, $sql);
    $row = mysqli_fetch_assoc($result);

    return $row['count'];
}
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
            padding: 10px;
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
            padding: 5px 10px 5px 10px;
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

    <script>
        function checkAndRedirect(type, limit) {
            <?php
            echo "const teacherCount = " . checkCount('teacher') . ";";
            echo "const classCount = " . checkCount('class') . ";";
            ?>

            if (type === 'teacher' && teacherCount >= limit) {
                alert('Maximum number of teachers (15) reached!');
                return false;
            }
            else if (type === 'class' && classCount >= limit) {
                alert('Maximum number of classes (15) reached!');
                return false;
            }
            else {
                window.location.href = 'addUser.php?role=' + type;
            }
        }
    </script>
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
                        <a href="javascript:void(0)" onclick="checkAndRedirect('teacher', 15)">➕</a>
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
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Year</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Semester</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Subject</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Class</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Student</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Assessment</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Result</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
                <tr class="sidebar-tr">
                    <td class="sidebar-td"><a href="admin.php" class="sidebar-link">Announcement</a></td>
                    <td class="sidebar-td sidebar-actions">
                        <a href="admin.php">➕</a>
                        <a href="admin.php">✏️</a>
                        <a href="admin.php">🗑️</a>
                    </td>
                </tr>
            </table>
        </div>

        <hr class="sidebar-hr">

        <div class="bottom-nav">
            <!-- <a href="settings.php" class="nav-item">⚙️ Settings</a> -->
            <a href="../index.php" class="nav-item">↩️ Logout</a>
        </div>
    </div>
</body>

</html>