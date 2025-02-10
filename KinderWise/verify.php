<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
    $name = $_POST["name"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM $role WHERE name='$name' AND password='$password'";
    $result = mysqli_query($conn, $sql);  
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);  
    $count = mysqli_num_rows($result);  

    if($count == 1){  
        // Redirect based on role
        switch ($role) {
            case "administrator":
                header("Location: admin.php");
                break;
            case "principal":
                header("Location: principal.php");
                break;
            case "teacher":
                header("Location: teacher.php");
                break;
            case "parent":
                header("Location: parent.php");
                break;
        }
        exit;
    } else {
        echo "Login failed. Invalid username or password. <a href='index.php'>Try Again</a>";
    }
}
?>


        
