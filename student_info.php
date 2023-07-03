
<?php

    session_start();
    include('syslogin_functions/config.php');
    error_reporting(1);

    if( !isset($_SESSION['user_id']) ) {
        header("location: login.php");
        exit();
    }

    if( isset($_POST["firstName"]) ) {

        // class id
        $cid = (int)$_SESSION['linkid'];

        // teacher id
        $tid = $_SESSION['user_id'];

        $firstName = $_POST["firstName"];
        $middleName = $_POST["middleName"];
        $lastName = $_POST["lastName"];

        // check if the first name and last name from input is already in the database
        $credCheck = "SELECT students.first_name, students.last_name, students.class_id FROM students 
                      WHERE students.first_name='$firstName' AND students.last_name='$lastName' AND students.class_id=$cid";

        $credQuery = mysqli_query($conn, $credCheck);
        if( mysqli_num_rows($credQuery) > 0 ) {

            // means there is already a record
            header("location: qr_scanner.php?error=alreadyexist");
            exit();

        }
        
        
        // sql syntax when inserting data to attendance sheet
        $query = "INSERT into students (first_name, middle_name, last_name, time_in, class_id, teacher_id) 
                  VALUES ('$firstName', '$middleName', '$lastName', NOW(),$cid, $tid)";

        // unset($_SESSION['linkid']);
        $result = mysqli_query($conn, $query);

        
        if( !$result ) {
            echo "Error: " . mysqli_error($conn);
        } else {
            $_SESSION['name'] = "$firstName " . "$lastName";
            header('location: qr_scanner.php');
        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Information</title>


    <link rel="stylesheet" href="css/styles1.css">
    <link rel="stylesheet" href="css/print.css" media="print">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">


</head>

<body>


<div class="sidenav">
        <div class="user-info">
            <i class="ri-user-2-fill"></i>
            <?php session_start() ?>
            <h4 class="username"><?php echo $_SESSION['fullname']; ?></h4>
            <p class="user-role">Instructor</p>
            <hr>
        </div>
        <ul class="nav-buttons">
            <li><a href="dashboard.php"><i class="ri-home-3-line"></i>Home</a></li>
            <?php require_once 'syslogin_functions/config.php'; ?>
            <?php
                // only show on nav the classes that have been added by the teacher/user
                $sql = "SELECT * FROM class WHERE teacher_id='$_SESSION[user_id]'";
                $result = mysqli_query($conn, $sql);
             ?>
             <?php while($row = mysqli_fetch_assoc($result) ) { ?>
                <!-- Get the id of the class in the url sid, cid, tid -->
                <li><a class="btn-clicked" href="student_info.php?cid=<?php echo $row['class_id']; ?>&csec=<?php echo $row['section']; ?>&ctid=<?php echo $row['teacher_id']; ?>"><i class="ri-booklet-line"></i><?php echo $row['subject_name'] ." ". $row['section'];?></a></li>
            <?php } ?>
            <li><a href="syslogin_functions/logout.php"><i class="ri-logout-circle-line"></i>Logout</a></li>
        </ul>
    </div>


    <div class="logo">
        <img src="images/aclclogo.png" class="aclclogo" alt="" width="200" height="200"> 
    </div>

    <!-- Search Section -->
    <!-- <div class="search-container">
        <form class="search-form" action="" method="POST">
            <input type="text" name="search" class="search-input" placeholder="Search for data">
            <button class="search-btn" name="submit">Search</button>
        </form>
    </div> -->

    <div class="table">
        <table>
            <tr>
                <th>No.</th>
                <th>First name</th>
                <th>Last name</th>
                <th>Subject</th>
                <th>Section</th>
                <!-- <th>Section</th> -->
            </tr>

            <?php $sNum = 0; $res = mysqli_query($conn, "SELECT students.student_id, students.first_name, students.last_name, class.class_id, class.subject_name, class.section
                                                         FROM students 
                                                         JOIN class 
                                                        --  check for section if class_id is equal to class_id in students as well as the teacher_id
                                                         ON students.class_id = class.class_id
                                                         WHERE students.class_id = '$_GET[cid]' "); ?>
            <?php while($row = mysqli_fetch_assoc($res)) { ?>
            <tr>
                <td><?php echo ++$snum; ?></td>
                <td><?php echo $row['first_name']; ?></td>
                <td><?php echo $row['last_name']; ?></td>
                <td><?php echo $row['subject_name']; ?></td>
                <td><?php echo $row['section']; ?></td>
                <td>
                    <button type="submit" class="update-btn" name="update-btn"><a href="operations/update.php?updateid=<?php echo $row['student_id']; ?>&classid=<?php echo $row['class_id']; ?>">Edit</a></button>
                    <button type="submit" class="delete-btn" name="delete-btn"><a href="operations/delete.php?deleteid=<?php echo $row['student_id']; ?>">X</a></button>
                </td>
            </tr>
            <?php } ?>
        </table>
    </div>


    <div class="bot-container">
        <div class="print-con">
            <button onclick="window.print()" class="print-btn"><i class="ri-printer-line"></i></button>
        </div>
        <div class="xls-con">
            <button><a class="export-btn" href="export_file.php?cid=<?php echo $_GET['cid'] ?>"><i class="ri-file-excel-2-line"></i></a></button>
        </div>
    </div>

</body>
</html>