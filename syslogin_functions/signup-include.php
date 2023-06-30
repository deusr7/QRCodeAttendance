
<?php

include("config.php");
include("functions.php");

if( isset($_POST['login-btn']) ) {

    header("location: ../index.php");
    exit();
}

// check if submit has been clicked

if( isset($_POST['signup']) ) {
    
    $usn = $_POST['usn'];
    $passwd = $_POST['passwd'];
    $name = $_POST['name'];
    $email = $_POST['email'];

 
    // check if inut is empty
    if( isEmpty($usn, $passwd, $name, $email) !== false ) {

        header("location: ../signup.php?error=emptyfields");
        exit();
    }

    //check if username is invalid
    if( isInvalidUsn( $usn ) !== false ) {

        header("location: ../signup.php?error=invaliduser");
        exit();

    }

    // check if username is already taken
    if( usnTaken($conn, $usn) !== false ) {

        header("location: ../signup.php?error=usernametaken");
        exit();

    }

    createUser($conn, $usn, $passwd, $name, $email);

} // else go back to signup page

else {

    header("location: ../signup.php");
    exit();

}
