

<?php

// export to excel

error_reporting(1);
require_once 'syslogin_functions/config.php';

header("Content-Type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=attendance.xls");
header('Pragma: no-cache');
header('Expires: 0');

    $output = "";

    $output .= "
    

        <table>
                <tr>
                    <th>No.</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Subject</th>
                    <th>Section</th>
                </tr>
            
            <tbody>
    
    ";
    $sNum = 0; 
    $res = mysqli_query($conn, "SELECT students.student_id, students.first_name, students.last_name, class.class_id, class.subject_name, class.section
                                                         FROM students 
                                                         JOIN class 
                                                        --  check for section if class_id is equal to class_id in students as well as the teacher_id
                                                         ON students.class_id = class.class_id
                                                         WHERE students.class_id = '$_GET[cid]' ");
    while($row = mysqli_fetch_assoc($res)) {
    $output .= " 
    
        <tr>
            <td>". ++$snum ." </td>
            <td>". $row['first_name'] ." </td>
            <td>". $row['last_name'] ." </td>
            <td>". $row['subject_name'] ." </td>
            <td>". $row['section'] ." </td>
        </tr>

    ";
    }

    $output .= "
    
            </tbody>
        </table>
    
    ";

    echo $output;