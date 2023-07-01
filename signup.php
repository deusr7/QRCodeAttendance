

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signup</title>
    
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>

<body>

    <div class="form-container">
        <form action="syslogin_functions/signup-include.php" method="POST">
            <h2 class="text-center">Sign Up</h2>
            <img src="images/sharingan.png" alt="" class="logo-svg">
            <div class="row ">
                <input type="text" class="form-control" name="usn" placeholder="Username" aria-label="First name">
                <input type="password" class="form-control" name="passwd" placeholder="Password" aria-label="Last name">
                <input type="text" class="form-control" name="name" id="formGroupExampleInput" placeholder="Name">
                <input type="text" class="form-control" name="email" id="formGroupExampleInput2" placeholder="Email">
                <!-- <input class="btn btnrprimary active" name="submit" type="submit" value="Sign Up"> -->
                <button class="btn btnrprimary active" type="submit" name="signup" >Sign Up</button>
                <input class="btn btnrprimary active" name="login-btn" type="submit" value="Log in">
            </div>
            
            <?php
                if(isset($_GET['error'])) {
                    if( $_GET['error'] === "emptyfields") {
                        echo "<p> Please fill up the fileds </p>";
                    } else if( $_GET['error'] === "invaliduser") {
                        echo "<p> Invalid Username </p>";
                    } else if( $_GET['error'] === "usernametaken") {
                        echo "<p> Username already taken </p>";
                    }
                }
            ?>
        </form>
    </div>
</body>
</html>