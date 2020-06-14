<?php
include("conn.php");
$currentCourse = $_GET["courseCode"];


// Basic Course info for nav
$sql = "SELECT courseName, courseCode, ects, numHours FROM course WHERE courseCode='$currentCourse';";
$info = mysqli_query($conn, $sql);

if(!$info){
    die("Query error". mysqli_error($info));
}
$info = mysqli_fetch_array($info);



//Instructors
$sql = "SELECT issn, iname, courseCode, sectionId, yearr, semester FROM weeklyschedule JOIN instructor on issn = ssn WHERE courseCode = '$currentCourse' GROUP BY sectionId ORDER BY yearr, semester;";
$inst = mysqli_query($conn, $sql);

if(!$inst){
    die("Query error". mysqli_error($inst));
}
$instructors=null;
while ($row = mysqli_fetch_array($inst)) {
    $instructors[] = $row;
}
$numOfIns = mysqli_num_fields($inst);


//Students 
$sql = "SELECT ssn, studentid, studentName, gradorUgrad, coursecode, sectionId, yearr, semester, grade FROM enrollment JOIN student on sssn = ssn  WHERE courseCode = '$currentCourse' ORDER BY yearr, semester,sectionId";
$stud = mysqli_query($conn, $sql);

if(!$inst){
    die("Query error". mysqli_error($stud));
}
$students=null;
while ($row = mysqli_fetch_array($stud)) {
    $students[] = $row;
}
$numOfStud = mysqli_num_fields($stud);


mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title><?php echo $info[0]?></title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <nav class="d-block">
            <div class="container">
                <?php 
            echo "<span>".$info[0]."</span>";
            echo "<span>".$info[1]."</span>";
                ?>
            </div>
        </nav>

        <div class="container">

            <!-- Instructors-->
            <div class="table-box inline-block mr-5">
                <span class="table-name">Instructor of <?php echo $info[0];?></span>

                <table cellspacing="0">
                    <tr>
                        <th>Name</th>
                        <th>Course&nbsp;Code</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>ECTS</th>
                        <th>Weekly&nbsp;Hours</th>
                    </tr>

                    <?php
                    if($instructors!==null){
                        foreach($instructors as $row){
                            echo "<tr>";

                            for($i=1; $i<$numOfIns; $i++){
                                if($i==1){  
                                    echo "<td><a href='instructor-detail.php?ssn=$row[0]' style='color:#1f2fad;'>$row[$i]</a></td>";
                                } else {
                                    echo "<td>".$row[$i]."</td>";
                                }    
                            }


                            echo "
                            <td>$info[2]</td>
                            <td>$info[3]</td>
                            <td><a href='instructor-detail.php?ssn=$row[0]'><div class='see-more'>More Detail</div></a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>

                </table>
            </div>

            <!-- Students -->
            <div class="table-box inline-block">
                <span class="table-name">Students of <?php echo $info[0];?></span>

                <table cellspacing="0">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Course&nbsp;Code</th>
                        <th>Section</th>
                        <th>Year</th>
                        <th>Semester</th>
                        <th>Grade</th>
                    </tr>

                    <?php
                    if($students!==null){
                        foreach($students as $row){
                            echo "<tr>";

                            for($i=1; $i<$numOfStud; $i++){
                                if($i==3 && $row[$i]==0){  //checking grad or under
                                    echo "<td>Under Graduate </td>";
                                } else if ($i==3 && $row[$i]==1){
                                    echo "<td>Graduate </td>";
                                }else if($i==$numOfStud-1 && $row[$i]===null){ //checking if grade is null
                                    echo "<td>-</td>";
                                }
                                else {
                                    echo "<td>".$row[$i]."</td>";
                                }

                            }

                            echo "<td><a href='student-detail.php?ssn=$row[0]'><div class='see-more'>More Detail</div></a></td>";
                            echo "</tr>";
                        }
                    }

                    ?>

                </table>
            </div>

        </div>


    </body>

</html>
