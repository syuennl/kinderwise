<!-- main page -->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KinderWise Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            text-align: center; 
            margin: 50px; 
            overflow: hidden; /* Hide scrollbars */
        }
        .container { 
            display: flex; 
            height: 80vh;  /* 100% of the viewport height */
            justify-content: center; /* Centers horizontally */
            align-items: center; /* Centers vertically */
            flex-direction: column; /* Aligns child elements vertically */
          }
        .logo { position: absolute; top: 10px; left: 10px; margin: 20px; }
        button { padding: 15px 25px; margin: 10px; font-size: 15px; cursor: pointer; background-color: #557FF7; color: white; border: none; border-radius: 5px; }
    </style>
</head>
<body>
    <div class="logo">
        <img src="./pics/logo.png" alt="KinderWise Logo" width="150px">
    </div>
    <div class="container">
        <h1>Welcome to KinderWise!</h1>
        <div class="buttons">
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="administrator">
                <button type="submit">Login as Administrator</button>
            </form>
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="principal">
                <button type="submit">Login as Principal</button>
            </form>
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="teacher">
                <button type="submit">Login as Teacher</button>
            </form>
            <form action="login.php" method="post">
                <input type="hidden" name="role" value="parent">
                <button type="submit">Login as Parent</button>
            </form>
        </div>
    </div>
</body>
</html>
