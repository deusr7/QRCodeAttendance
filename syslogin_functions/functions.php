
<?php
include("config.php");


// SIGNUP FUNCTIONS

//check if input is empty
function isEmpty( $usn, $passwd, $name, $email ) {

    if( empty($usn) || empty($passwd) || empty($name) || empty($email) ) {
        return true;
    } else {
        return false;
    }
}

//check if username is invalid
function isInvalidUsn( $usn ) {

    // if the username does not contain the pattern requirement
    // return true
    if( !preg_match("/^[a-zA-Z0-9]*$/", $usn) ) {
        return true;
    } else { return false; }
}

//check if username is already taken
function usnTaken( $conn, $usn ) {

    // check if username is already in the database
    // use a placeholder in query 
    $sql = " SELECT * FROM scan_users WHERE userName = ?";

    // create a prepared statement
    // initialize by connecting to database
    $stmt = mysqli_stmt_init( $conn );

    // prepare the prepared statement 
    // check for errors otherwise insert the data
    if( !mysqli_stmt_prepare($stmt, $sql) ) {
        
        header("location: ../signup.php");
        exit();

    }

    //bind the data if it has no errors
    // ( initialized statement, input data type, actual value we want to bind)
    mysqli_stmt_bind_param($stmt, "s", $usn);
    //execute the statement with the stmt as param to idntfy which 
    mysqli_stmt_execute($stmt);

    // get the result 
    $res = mysqli_stmt_get_result($stmt);

    // check if result has a value. means username is taken
    if( $row = mysqli_fetch_assoc($res) ) {
        return $row;
    } else { return false; }

}

function createUser($conn, $usn, $passwd, $name, $email) {

    // create a prepared statement template
    $sql = "INSERT into scan_users (userName, passWord, fullName, email)
     VALUES (?, ?, ?, ?)";

    // initialize new prepared statement
    $stmt = mysqli_stmt_init( $conn );

    if( !mysqli_stmt_prepare($stmt, $sql) ) {
        header("location: ../signup.php");
        exit();
    }

    // hash the password before binding to the template
    $hashedPasswd = password_hash( $passwd, PASSWORD_DEFAULT);

    // replace the passwd value to hashedpasswd
    mysqli_stmt_bind_param($stmt, "ssss", $usn, $hashedPasswd, $name, $email);
    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);

    header("location: ../signup.php");
    exit();

}

// LOGIN FUNCTIONS 
function isEmptyLogin( $usn, $passwd) {

    if( empty($usn) || empty($passwd) ) {
        return true;
    } else {
        return false;
    }
}

function loginUser($conn, $usn, $passwd ) {

    // check if username is taken using usnTaken func()
    $usnExist = usnTaken($conn, $usn);

    // compare it equal to false to incorporate the condition inside usnTaken function
    if( $usnExist === false ) {
        header("location: ../index.php?error=errorlogin");
        exit(); 
    }

    // compare the hashed password inside the db with passwd input
    $hashedPass = $usnExist["passWord"];
    // store the verified password in a checker
    $checkPass = password_verify($passwd, $hashedPass);

    if( $checkPass === false ) {

        header("location: ../index.php?error=incorrectpass");
        exit();

    } else if( $checkPass === true ) {

        // start a session
        session_start();
        // store the selected username in db to a sessions var
        $_SESSION['fullname'] = $usnExist['fullName'];
        $_SESSION['user_id'] = $usnExist['id'];
        // now login the user to your home page
        header("location: ../dashboard.php");
        exit();
    }
}