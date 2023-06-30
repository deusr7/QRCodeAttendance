
<?php 

    session_start();
    error_reporting(1);
    // get the user's name via session that was created in login funcs
    $tid = $_SESSION['user_id'];

    if( !isset($_SESSION['user_id']) ) {

        header("location: index.php");
        exit();

    }


    // php script to retrieve weather data from OpenWeather API
    $apiKey = "02d2a51e7b019435c617a4dba2f0246e";

    $lat = "11.237490";
    $long = "124.989166";

    

    $googleApiUrl = "https://api.openweathermap.org/data/2.5/weather?lat=" . $lat . "&lon=" . $long . "&appid=". $apiKey;


    // cURL Initialization:
    $ch = curl_init();

    // This function initializes a cURL session, which is used to make HTTP requests.

    // cURL Options:
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $googleApiUrl);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_VERBOSE, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    // Execute the cURL Request:
    $response = curl_exec($ch);

    // Close the cURL Session:
    curl_close($ch);

    // Process the Response:
    $data = json_decode($response);

    // echo "<pre>";
    // print_r($data);
    // echo "</pre>";
    // die;

    $currentTime = time();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <link rel="stylesheet" href="css/styles1.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@3.2.0/fonts/remixicon.css" rel="stylesheet">
</head>
<body>

    <div class="sidenav">
        <div class="user-info">
            <i class="ri-user-2-fill"></i>
            <h4 class="username"><?php echo $_SESSION['fullname']; ?></h4>
            <p class="user-role">Instructor</p>
            <div class="report-container">
                <!-- OpenWeatherMap API  -->
                <h4><?php echo $data->name; ?> Weather </h4>
                <div class="time">
                    <div><?php echo ucwords($data->weather[0]->description); ?></div>
                </div>
                <div class="weather-forecast">
                    <img
                        src="https://openweathermap.org/img/w/<?php echo $data->weather[0]->icon; ?>.png"
                        class="weather-icon" /> 
                        <span class="min-temperature"><?php echo $data->main->temp_min - 273.15; ?> °C</span>
                </div>
                <div class="time">
                    <div>Humidity: <?php echo $data->main->humidity; ?> %</div>
                    <div>Wind: <?php echo $data->wind->speed; ?> km/h</div>
                </div>
            </div>
            <hr>
        </div>
        <ul class="nav-buttons">
            <li><a href="dashboard.php"><i class="ri-home-3-line"></i>Home</a></li>
            <?php require_once 'syslogin_functions/config.php'; ?>
            <?php


                // only show on nav the classes that have been added by the teacher/user
                
                $sql = "SELECT * FROM class WHERE teacher_id=$tid";
                $result = mysqli_query($conn, $sql);
             ?>
             <?php while($row = mysqli_fetch_assoc($result) ) { ?>
                <!-- Get the id of the class in the url -->
                <li><a id="btn-clicked" href="student_info.php?cid=<?php echo $row['class_id']; ?>&csec=<?php echo $row['section']; ?>&ctid=<?php echo $row['teacher_id']; ?>"><i class="ri-health-book-line"></i><?php echo $row['subject_name'] ." ". $row['section'];?></a></li>
            <?php } ?>
            
            <li><a href="syslogin_functions/logout.php"><i class="ri-logout-circle-line"></i>Logout</a></li>
        </ul>
    </div>

    <div class="main-content">
        <div class="dashboard">
            <div class="class" onclick="showRegistrationForm()"><i class="ri-add-line"></i>Create class</div>

        </div>

        <div class="overlay" id="registrationFormOverlay">
            <div class="registration-form">
                <form class="form" action="main_functions/create_class.php" method="POST">
                    <input type="text" id="subject_name" placeholder="Subject name" name="subName">
                    <input type="text" id="section" placeholder="Section"name="section">
                    <input type="text"  placeholder="Day"name="day">
                    <input type="text"  placeholder="Time"name="time">

                    <button type="submit" name="create">Create</button>

                </form>
                <button class="close-btn" onclick="hideRegistrationForm()">Close</button>
            </div>
        </div>

        <?php

            require_once 'syslogin_functions/config.php';

            // select only the account id when creating a class
            $sql = "SELECT * FROM class WHERE teacher_id=$tid";
            $result = mysqli_query($conn, $sql);
        
        ?>

        <div class="class-container">
            <?php while($row = mysqli_fetch_assoc($result) ) { ?>
                <div class="class-display">
                    <!-- <pre> -->
                        <div class="dashboard-row">
                            <!-- <label for="subject" >Subject: </label> -->
                           <?php echo $row['subject_name']; ?>
                        </div>
                        <div class="dashboard-row">
                            <!-- <label for="subject" >Section: </label> -->
                            <?php echo $row['section']; ?>
                        </div>
                        <div class="dashboard-row">
                            <!-- <label for="subject" >Section: </label> -->
                            <?php echo "<i class='ri-sun-line'></i>" . $row['class_day']; ?>
                        </div>
                        <div class="dashboard-row">
                            <!-- <label for="subject" >Section: </label> -->
                            <?php echo "<i class='ri-time-line'></i>" . $row['class_time']; ?>
                        </div>
                        <div class="dashboard-row">
                            <!-- Scanner link -->
                            <button><a href="qr_scanner.php?onscan=<?php echo $row['class_id'];?>"><i class="ri-qr-scan-2-line"></i></a></button>
                            <button><a href="main_functions/delete_class.php?deleteid=<?php echo $row['class_id']; ?>"><i class="ri-delete-bin-line"></i></a></button>
                        </div>
                        
                    <!-- </pre> -->
                </div>
            <?php } ?>
        </div>
        
        <?php 
            
            if( isset($_GET['error']) ) {
                if( $_GET['error'] === "classexist" ) {
                echo "<p> Class Already Exist </p>";
                }
            }
        ?>
    </div>

    <script src="javascript/dashboard.js"></script>

    <script>

        let button = document.getElementById("btn-clicked");

        button.addEventListener("click", function() {
        button.classList.add("button-clicked");
        });
    </script>
    
</body>
</html>