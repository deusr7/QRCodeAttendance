
<?php
    session_start();
    error_reporting(1);
    if( !isset($_SESSION['user_id']) ) {
        header("location: login.php");
        exit();
    }
 ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scan QR Code</title>
    <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script> -->

    <link rel="stylesheet" href="css/styles.css">

    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
    <!-- Javascript links to include to use the instascan lib -->
    <!-- link: https://www.sourcecodester.com/tutorial/php/14777/how-read-qr-code-using-webcam-scanner-js-instascanjs-reading-qr-code-free-source -->
    <script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.1.10/vue.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/webrtc-adapter/3.3.3/adapter.min.js"></script>
    

<body>

    <div class="sidenav">
        <div class="user-info">
            <i class="ri-user-2-fill"></i>
            <h4 class="username"><?php echo $_SESSION['fullname']; ?></h4>
            <p class="user-role">Instructor</p>
            <hr>
        </div>
        <ul class="nav-buttons">
            <li><a href="dashboard.php"><i class="ri-home-3-line"></i>Home</a></li>
            <?php require_once 'syslogin_functions/config.php'; ?>
            <?php
                $sql = "SELECT * FROM class WHERE teacher_id='$_SESSION[user_id]'";
                $result = mysqli_query($conn, $sql);
             ?>
             <?php while($row = mysqli_fetch_assoc($result) ) { ?>
                <!-- passing the class id to the url -->
                <li><a href="student_info.php?cid=<?php echo $row['class_id'];?>&csec=<?php echo $row['section']; ?>&ctid=<?php echo $row['teacher_id']; ?>"><i class="ri-health-book-line"></i><?php echo $row['subject_name'] ." ". $row['section'];?></a></li>
            <?php } ?>
            <li><a href="syslogin_functions/logout.php"><i class="ri-logout-circle-line"></i>Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="scanner-row">
            <video id="preview"></video>
        </div>
        <div class="scanner-row">
            <!-- <button id="start" onclick="onStart()" >start</button> -->
            <button id="stop" class="stop" onclick="onStop()" >stop</button>
        </div>

        <form id="myForm" class="hide" action="student_info.php" method="post"> 
          
            <input type="hidden" id="firstName" name="firstName">
            <input type="hidden" id="midName" name="middleName">
            <input type="hidden" id="lastName" name="lastName">
            <input type="hidden" id="section" name="section">

            <?php

                // get the id of the url
                $_SESSION['linkid'] = $_GET['onscan'];
                $name = $_SESSION['name'];
        
                if( isset($_SESSION['name']) ) { 

                    echo "<h2 id='hideName'> $name </h2>
                    <i class='ri-check-line' id='hideCheck'></i>";
                    unset($_SESSION['name']);

                }

                if( isset($_GET['error']) ) {
                    if( $_GET['error'] === "alreadyexist" ) {
                        echo "<h2> $name Already Recorded </h2>";
                    }
                }
            ?>

        </form>
    </div>

    <script type="text/javascript" src="javascript/scan.js"></script>

</body>
</html>