
<?php

    session_start();
    error_reporting(1);


    // if a session has been started, you cant go back to this file
    if( isset($_SESSION['user_id']) ) {

        header("location: dashboard.php");
        exit();

    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <link rel="icon" href="images/sharingan.png" type="image/x-icon">


    <link rel="stylesheet" href="css/styles1.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
  
</head>

<body>

    <div class="form-container">
        <form action="syslogin_functions/login-include.php" method="POST">
            <h2 class="">Attendance System</h2>
            <img src="images/sharingan.png" alt="" class="logo-svg">
            <div class="row ">
                
                    <input type="text" class="form-control " name="usn" placeholder="Username" aria-label="First name">
                    <input type="password" class="form-control" name="passwd" placeholder="Password" aria-label="Last name">
             
                <!-- <input class="btn active" type="submit" name="submit" value="Login"> -->
                <button class="btn active" type="submit" name="submit">Login</button>
                <a class="nav-link active" aria-current="page" href="signup.php">Sign Up?</a>
            </div>
            <?php
                // check if the error var is set in url
                if( isset($_GET['error']) ) {

                    if( $_GET['error'] === "emptyfields" ) {
                       echo "<p> Please fill the fields </p>";
                    } else if( $_GET['error'] === "incorrectpass" ) {
                        echo "<p> incorrect password </p>";
                    } else if( $_GET['error'] === "errorlogin" ) {
                        echo "<p> Error Login </p>";

                    }
                }
            ?>
        </form>
    </div>

</body>
</html>