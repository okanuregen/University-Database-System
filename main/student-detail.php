<?php
error_reporting(0);
include ( 'conn.php' );
$currentSsn = $_GET['ssn'];

// Basic Info
$sql = "SELECT S.studentname, S.studentid,S.gradorUgrad, I.iname, S.dName,I.ssn, S.currCode FROM student S JOIN instructor I ON S.advisorSsn=I.ssn WHERE S.ssn='$currentSsn';";
$info = mysqli_query( $conn, $sql );

if ( !$info ) {
    die( 'Query error'. mysqli_error( $info ) );
}
$info = mysqli_fetch_array( $info );



//Weekly Schedule
$sql = "SELECT dayy, hourr, courseCode, sectionId, buildingName, roomNumber FROM weeklyschedule NATURAL JOIN enrollment WHERE sssn = '$currentSsn' and yearr = 2020 and semester = 2 ORDER BY dayy, hourr;";

$schedule = mysqli_query( $conn, $sql );

if ( !$schedule ) {
    die( 'Query error'. mysqli_error( $schedule ) );
}

//Prev Degree (if graduate)
$sql = "SELECT * FROM prevdegree WHERE gradssn = '$currentSsn';";

$prev = mysqli_query($conn, $sql);
if ( !$prev ) {
    die( 'Query error'. mysqli_error( $prev ) );
}

//Curriculum Lessons
$sql="SELECT C.courseCode, C1.courseName, C1.ects
FROM (curriculacourses C NATURAL JOIN Course C1) 
WHERE C.currCode='$info[6]';";

$curricul = mysqli_query($conn,$sql);
if(!$curricul){
    die('<p class="warning">Query Error</p>'.mysqli_error($curricul));
}
$resultset = null;
$numOfFields = mysqli_num_fields($curricul);
while($row = mysqli_fetch_array($curricul)){
    $resultset[]=$row;
}

//Grades for Curriculum
$sql="SELECT DISTINCT C.courseCode, E.grade
FROM (curriculacourses C NATURAL JOIN enrollment E) 
WHERE E.sssn='$currentSsn';";

$grad = mysqli_query($conn,$sql);
if(!$curricul){
    die('<p class="warning">Query Error</p>'.mysqli_error($grad));
}
$grades = null;
while($row = mysqli_fetch_array($grad)){
    $grades[]=$row;
}



// Current Courses 
$sql ="select E.courseCode, C.courseName, C.ects
from enrollment E NATURAL JOIN course C
where E.sssn='$currentSsn' and E.yearr='2020' and E.semester='2';";


$courses = mysqli_query($conn, $sql);
if(!$courses){
    die( '<p class="warning">Query Error</p>'. mysqli_error( $courses ) );
}
$resultset1 = null;
$numOfFields1 = mysqli_num_fields( $courses );
while( $row = mysqli_fetch_array( $courses ) ) {
    $resultset1[] = $row;
}


//Works On Projects
$worksOnColNames = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'p1solution3' AND TABLE_NAME = 'project_has_gradstudent';";
$worksOnColNames = mysqli_query( $conn, $worksOnColNames );
if ( !$worksOnColNames ) {
    die( '<p class="warning">Query Error</p>'. mysqli_error( $worksOnColNames ) );
}

$sql = "SELECT * FROM project_has_gradstudent WHERE Gradssn ='$currentSsn';";
$projects = mysqli_query( $conn, $sql );
if ( !$projects ) {
    die( '<p class="warning">Query Error</p>'. mysqli_error( $projects ) );
}
$resultset2 = null;
$numOfFields2 = mysqli_num_fields( $projects );
while ( $row = mysqli_fetch_array( $projects ) ) {
    $resultset2[] = $row;
}

//Days for the schedule table
$days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];

mysqli_close( $conn );
?>

<!DOCTYPE html>
<html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <title><?php echo $info[0]?></title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
        <style>
            .schedule td {
                padding: 0;
            }

            .schedule th {
                padding: 20px 40px;
            }

            .fs-08 {
                font-size: 0.8rem;
            }

            .fs-08 th {
                font-size: 1rem;
            }

        </style>
    </head>


    <body>

        <nav class="d-block">
            <div class="container">
                <?php 
    if($info[2]=='1'){ $info[2]="Graduate";
                     }else { $info[2]="Under Graduate";}
            echo "<span>".$info[0]."</span>";
            echo "<span>".$info[1]."</span>";
            echo "<span style='text-transform: capitalize;'>".$info[2]."</span>";
            echo "<span>"."<a href='instructor-detail.php?ssn=$info[5]'> $info[3] </a>"."</span>";
            echo "<span>"."<a href='department-detail.php?dname=$info[4]'> $info[4] </a>"."</span>";

                ?>
            </div>
        </nav>

        <div class="container">

            <!-- Weekly Schedule-->
            <div class="table-box inline-block fs-08">
                <span class="table-name">Weekly Schedule</span>
                <table cellspacing="0" class="schedule">

                    <tr>
                        <th></th>
                        <?php
                        for($i=1; $i<=13; $i++){
                            echo "<th>".$i."</th>";
                        }
                        ?>
                    </tr>

                    <?php
                    for($d = 0; $d<count($days); $d++){

                        echo "<tr> <th>$days[$d]</th>";

                        $i=1;
                        foreach($schedule as $row){
                            if($row["dayy"]==$days[$d]){
                                while($i<=13){
                                    if($i==$row["hourr"]){
                                        echo "<td  class='upper-case'> 
                                <span class='d-block'>".$row["courseCode"].".".$row["sectionId"]."</span>

                                <span class='d-block'>".$row["buildingName"]." ".$row["roomNumber"]."</span>

                                </td>";
                                        $i++;
                                        break;
                                    }else{
                                        $i++;
                                        echo "<td></td>";
                                    }
                                }
                            }
                        }
                        while($i<=13){
                            echo "<td></td>";
                            $i++;
                        }
                        echo "</tr>";
                    }

                    ?>
                </table>
            </div>

            <div class="inline-block f-left mr-5">
                <!-- Prev Degrees -->

                <div class="table-box <?php 
                            if($info[2]==='Under Graduate'){
                                echo "d-none";
                            }
                            ?>"> 
                    <span class='table-name'>Previous Degrees</span>
                    <table cellspacing='0'>

                        <tr>
                            <th>Collage</th>
                            <th>Degree</th>
                            <th>Year</th>

                        </tr>
                        <?php

                        while($row=mysqli_fetch_array($prev)){
                            echo '<tr>';

                            for($i=0;$i<3;$i++){
                                echo '<td>'.$row[$i].'</td>';
                            }
                            echo '</tr>';
                        }

                        ?>
                    </table>
                </div>


                <!-- Curriculum -->
                <div class="table-box inline-block  mr-5">
                    <span class='table-name'>Curriculum</span>
                    <table cellspacing='0'>
                        <tr>
                            <th>Course Code</th>
                            <th>Course Name </th>
                            <th>ECTS</th>
                            <th>Course Grade</th>
                        </tr>
                        <?php
                        if($resultset!==null){
                            foreach($resultset as $row){
                                echo '<tr>';

                                for($i=0;$i<$numOfFields;$i++){

                                    echo '<td>'.$row[$i].'</td>';

                                }
                                $check = false;
                                if($grades!==null){
                                    foreach($grades as $row2){
                                        if($row2[0]==$row[0]){
                                            if($row2[1]!==null){
                                                echo "<td>$row2[1]</td>";
                                            }else{
                                                echo "<td>-</td>";
                                            }
                                            $check = true;
                                        }
                                    }
                                }
                                if(!$check){
                                    echo "<td>-</td>";
                                }
                                echo "<td><a href='course-detail.php?courseCode=$row[0]'><div class='see-more'>More Detail</div></a></td>";
                                echo "</tr>";
                            }
                        }

                        ?>
                    </table>
                </div>
            </div>

            <div class="inline-block f-left">
                <!-- Cureent Courses -->
                <div class="table-box">
                    <span class='table-name'>This Semester's Courses</span>
                    <table cellspacing='0'>

                        <tr>
                            <th>Course Code</th>
                            <th>Course Name</th>
                            <th>ECTS</th>

                        </tr>
                        <?php
                        if($resultset1!=null){
                            foreach($resultset1 as $row){
                                echo '<tr>';

                                for($i=0;$i<$numOfFields1;$i++){
                                    echo '<td>'.$row[$i].'</td>';
                                }
                                echo "<td><a href='course-detail.php?courseCode=$row[0]'><div class='see-more'>More Detail</div></a></td>";


                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>

                <!-- Project -->
                <div class="table-box <?php 
                            if($info[2]==='Under Graduate'){
                                echo "d-none";
                            }
                            ?>">
                    <span class='table-name'>Projects</span>
                    <table cellspacing='0'>

                        <tr>
                            <?php
                            while( $row = mysqli_fetch_array( $worksOnColNames ) ) {
                                echo '<th>'.$row[0].'</th>';
                            }

                            ?>
                        </tr>

                        <?php
                        if ( $resultset2 !== null ) {
                            foreach ( $resultset2 as $row2 ) {
                                echo '<tr>';

                                for ( $i = 0; $i<$numOfFields2; $i++ ) {

                                    echo '<td>'.$row2[$i].'</td>';

                                }
                                echo "<td><a href='project-detail.php?leadSsn=$row2[0]&pName=$row2[1]'><div class='see-more'>More Detail</div></a></td>";
                                echo '</tr>';
                            }
                        }
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </body>

</html>
