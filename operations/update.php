
<?php 


    error_reporting(1);
    session_start();
    require_once '../syslogin_functions/config.php';

    if( !isset($_SESSION['user_id']) ) {
        header("location: ../login.php");
        exit();
    }

    // get id
    // convert the url id to int bc of di mabatid na error
    $sid = (int)$_GET["updateid"]; 
    $cid = (int)$_GET["classid"];
    
    // check if form has been submitted
    if( isset($_POST['submit']) ) {

        // set the input variables
        $fname = $_POST['first'];
        $lname = $_POST['last'];
        $subjname = $_POST['subject'];
        $section = $_POST['section'];

        if( empty($fname) || empty($lname) || empty($subjname) ||
            empty($section) ) {

                header("location: update.php?error=emptyfield");
                exit();
        }

        // create query for update using merge
        $studentQuery = "UPDATE students SET first_name = '$fname', last_name = '$lname' WHERE student_id = $sid";
        $classQuery = "UPDATE class  SET subject_name = '$subjname', section = '$section' WHERE class_id = $cid";
        $result = mysqli_query($conn, $studentQuery);
        $result2 = mysqli_query($conn, $classQuery);

        if( $result && $result2 ) {
            header("location: update.php?error=updated");
            exit();
        } else {
            die(mysqli_error($conn));
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update</title>

    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>

    <div class="sidenav">
        <div class="user-info">
            <i class="ri-user-2-fill"></i>
            <h4 class="username"><?php echo $_SESSION['fullname']; ?></h4>
            <p class="user-role">Instructor</p>
            <hr>
        </div>
        <ul class="nav-buttons">
            <li><a href="../dashboard.php"><i class="ri-home-3-line"></i>Home</a></li>
            
            <?php


                // only show on nav the classes that have been added by the teacher/user
                $tid = $_SESSION['user_id'];
                $sql = "SELECT * FROM class WHERE teacher_id=$tid";
                $result = mysqli_query($conn, $sql);
             ?>
             <?php while($row = mysqli_fetch_assoc($result) ) { ?>
                <!-- Get the id of the class in the url -->
                <li><a id="btn-clicked" href="../student_info.php?cid=<?php echo $row['class_id']; ?>&csec=<?php echo $row['section']; ?>&ctid=<?php echo $row['teacher_id']; ?>"><?php echo $row['subject_name'] ." ". $row['section'];?></a></li>
            <?php } ?>
            
            <li><a href="../syslogin_functions/logout.php">Logout</a></li>
        </ul>
    </div>

    <div class="main-content">


    <form class="update-form" method="POST">

        <!-- Specify which field to update -->

        <?php 
        
            $res = mysqli_query($conn, "SELECT students.first_name, students.last_name, class.subject_name, class.section
                                        FROM students 
                                        JOIN class 
                                        ON students.class_id = class.class_id
                                        WHERE students.student_id = $sid AND class.class_id=$cid");
      
        ?>

        <?php while($row = mysqli_fetch_assoc($res) ) { ?>

        <input type="text" placeholder="First name" name="first" value="<?php echo $row['first_name']; ?>">
        <input type="text" placeholder="Last Name" name="last" value="<?php echo $row['last_name']; ?>">
        <input type="text" placeholder="Subject" name="subject" value="<?php echo $row['subject_name']; ?>">
        <input type="text" placeholder="Section" name="section" value="<?php echo $row['section']; ?>">
        <input type="submit" class="update-btn" name="submit" value="Update">
        
        <?php } ?>

        <?php 
        
            if( isset($_GET['error']) ) {
                if( $_GET['error'] === "emptyfield" ) {
                    echo "<p> Please fill up the forms </p>";
                } else if( $_GET['error'] === "usertaken" ) {
                    echo "<p> Username already taken </p>";
                } else if( $_GET['error'] === "updated" ) {
                    echo "<p> User has been updated! </p>";
                }
            }
        
        ?>
    </form>
    </div>
    
</body>
</html>
