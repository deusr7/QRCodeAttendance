
<?php

require_once '../syslogin_functions/config.php';

if( isset($_GET['deleteid']) ) {

    $class_id = $_GET['deleteid'];

    $sql = "DELETE FROM students WHERE class_id=$class_id";
    $sql2 = "DELETE FROM class WHERE class_id=$class_id";

    $result = mysqli_query($conn, $sql);
    $result2 = mysqli_query($conn, $sql2);

    if( $result && $result2 ) {

        header("location: ../dashboard.php");
        exit();

    }
}