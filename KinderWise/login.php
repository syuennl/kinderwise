<!-- login page -->

<?php
include("connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $role = $_POST["role"];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body { font-family: Arial, sans-serif; text-align: center; margin: 50px; }
        button { padding: 10px 20px; font-size: 16px; cursor: pointer; background-color: #557FF7; color: white; border: none; border-radius: 5px; }
        input { padding: 5px; font-size: 16px; margin: 15px; }
        .logo { position: absolute; top: 10px; left: 10px; margin: 20px; }
        .container { display: flex; height: 80vh; justify-content: center; align-items: center; flex-direction: column; }
    </style>
</head>
<body>
    <div class="logo">
        <img src="logo.png" alt="KinderWise Logo" width="150px">
    </div>
    <div class="container">
        <h1>Login as <?php echo ucfirst($role); ?></h1> <!-- ucfirst() use to capitalized first character -->
        <form action="verify.php" method="post">
            <input type="hidden" name="role" value="<?php echo $role; ?>">
            <label for="name">Name:</label>
            <input type="text" name="name" required><br>
            <label for="password">Password:</label>
            <input type="password" name="password" required><br>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>
