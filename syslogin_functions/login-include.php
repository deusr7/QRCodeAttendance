
<?php
include('functions.php');
include('config.php');


if( isset($_POST['submit']) ) {

    $usn = $_POST['usn'];
    $passwd = $_POST['passwd'];


    if( isEmptyLogin($usn, $passwd) !== false ) {
        header("location: ../index.php?error=emptyfields");
        exit();
    }

    loginUser($conn, $usn, $passwd);

} else {
    header("location: ../index.php");
    exit();
}