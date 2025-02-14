<!-- admin dashboard -->
<?php
include("sidebar.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrator Dashboard</title>
    <style>
        body {
            font-family: Trebuchet MS, sans-serif;
            text-align: center;
            margin: 0;
            background-color: #f4f4f4;
            overflow-x: hidden;
        }


        .container {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            flex-grow: 1;
            height: 80vh;
            margin-left: 340px; /* Adjusted to account for sidebar width + padding */
        }
    </style>
</head>
<body>
    
    <div class="container">
        <h1>Welcome, Administrator!</h1>
    </div>
</body>
</html>