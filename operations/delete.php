


<?php


require_once '../syslogin_functions/config.php';

if( isset($_GET['deleteid']) ) {

    $sql = "DELETE FROM students WHERE student_id = $_GET[deleteid]";

    $result = mysqli_query($conn, $sql);

    if( $result ) {
        header("location: ../student_info.php");
        exit();
    } else {
        die(mysqli_error($conn));
    }
}

