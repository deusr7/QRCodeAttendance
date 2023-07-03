
<?php
session_start();
require_once '../syslogin_functions/config.php';

if( isset($_POST['create']) ) {

    $teacher_id = $_SESSION['user_id'];
    $subName = $_POST['subName'];
    $sec = $_POST['section'];
    $day = $_POST['day'];
    $time = $_POST['time'];
    
    // check if the fields are empty
    if( !empty($subName) && !empty($sec) && !empty($day) && !empty($time) ) {
        // check for existing data
        $sqlCheck = "SELECT * FROM class WHERE subject_name='$subName' AND section='$sec' AND class_day='$day' AND class_time='$time'";
        $queryCheck = mysqli_query($conn, $sqlCheck);

        if( mysqli_num_rows($queryCheck) > 0) {

            // duplication found
            header("location: ../dashboard.php?error=classexist");
            exit();            
        };

        // insert teacher id to determine who created the class
        $sql = "INSERT INTO class (subject_name, section, teacher_id, class_day, class_time) VALUES ('$subName', '$sec', $teacher_id, '$day', '$time') ";
        $result = mysqli_query($conn, $sql);

        if( !$result ) {

            die(mysqli_error($conn));

        } else {
            header("location: ../dashboard.php");
        }

    } else {

        header("location: ../dashboard.php");
        echo "<script> alert('fill all the fields') </script>";

    }
} 

else {
    header("location: ../dashboard.php");
    exit();
}