<?php
include ("conn.php");
include ("functions.php");
$currentSsn = $_GET["ssn"];



// Name Rank Dep and Base Salary info
$sql = "SELECT iname, rankk, dName, baseSal FROM instructor WHERE ssn='$currentSsn';";
$info = mysqli_query($conn, $sql);

if(!$info){
    die("Query error". mysqli_error($info));
}
$info = mysqli_fetch_array($info);
$baseSalary=$info["baseSal"];



//Weekly Schedule
$sql = "SELECT dayy, hourr, courseCode, sectionId, buildingName, roomNumber FROM weeklyschedule WHERE issn = '$currentSsn' and yearr = 2020 and semester = 2 ORDER BY dayy, hourr;"; 
$schedule = mysqli_query($conn,$sql);

if(!$schedule){
    die("Query error". mysqli_error($schedule));
}


//to find the lessons
$sql = "SELECT distinct courseCode, sectionId from weeklyschedule where issn='$currentSsn' and yearr = 2020 and semester = 2;";
$lessons = mysqli_query($conn, $sql);
if(!$lessons){
    die("Query error". mysqli_error($lessons));
}
$allLessons[]=null;
$les[]=null;
$i=0;
while($row=mysqli_fetch_array($lessons)){
    $allLessons[$i] = $row[0]; //to keep just lesson
    $les[$i]=$row[0].".".$row[1]; //to keep lessons and section
    $i++;
}

if(isset($_GET['lesson']) && $_GET['lesson']!=""){
    $currentLesson = $_GET['lesson'];
}else{
    $currentLesson=$les[0];
}

//Free Hours Report for Students
$freeHours=null;
if($currentLesson!==null){
    $lesAndSect = explode(".",$currentLesson);
    $curLes = $lesAndSect[0];
    $curSec = $lesAndSect[1];
    $freeHours = freeHours($conn, $currentSsn, $curLes, $curSec); //to keep free hours
}


//Course Informations

$sql ="SELECT * FROM  course WHERE";
for($i=0; $i<count($allLessons); $i++){
    if($i!=count($allLessons)-1){
        $sql = $sql." courseCode='$allLessons[$i]' or";
    } else {
        $sql = $sql." courseCode='$allLessons[$i]';";
    }

}

$AllLesRes = mysqli_query($conn, $sql);
if(!$AllLesRes){
    die("Query error". mysqli_error($AllLesRes));
}


//projects lead by
$sql = "SELECT * FROM project WHERE leadSsn = '$currentSsn' ORDER BY pName;";
$mainProjects = mysqli_query($conn,$sql);

if(!$mainProjects){
    die("Query error". mysqli_error($mainProjects));
}
$resultset = null;
$numOfFields = mysqli_num_fields($mainProjects);
while ($row = mysqli_fetch_array($mainProjects)) {
    $resultset[] = $row;
}


//Works On Projects
$sql = "SELECT leadSsn, pName, workinghour FROM project_has_instructor WHERE issn ='$currentSsn';";
$projects = mysqli_query($conn,$sql);
if(!$projects){
    die('<p class="warning">Query Error</p>'. mysqli_error($projects));
}
$resultset2 = null;
$numOfFields2 = mysqli_num_fields($projects);
while ($row = mysqli_fetch_array($projects)) {
    $resultset2[] = $row;
}


//Students whose advisor is here

$sql = "SELECT ssn, gradorUgrad, currCode, dName, studentid, studentname FROM student WHERE advisorSsn='$currentSsn';";
$students=mysqli_query($conn,$sql);
if(!$students){
    die('<p class="warning">Query Error</p>'. mysqli_error($students));
}
$resultset3 = null;
$numOfFields3 = mysqli_num_fields($students);
while ($row = mysqli_fetch_array($students)) {
    $resultset3[] = $row;
}


//EXTRA SALARIES 
//for each project 
$sql = "SELECT 100*sum(P.workinghour)
FROM project_has_instructor P
WHERE P.issn='$currentSsn';";
$FromProject = mysqli_query($conn,$sql);
if(!$FromProject){
    die('<p class="warning">Query Error</p>'. mysqli_error($extraFromProject));
}
$extraFromProject = 0;
while($row=mysqli_fetch_array($FromProject)){
    $extraFromProject = floatval($row[0]);
}
if($extraFromProject<0) { $extraFromProject=0;}


//for weekly hours
$sql = "select (sum(course.numHours)-10)*50 as extraCoursePayment
from sectionn NATURAL JOIN course
where issn = '$currentSsn' and ((sectionn.yearr = 2020 and sectionn.semester=2 ) or (sectionn.yearr is null and sectionn.semester is null));";
$FromWeek = mysqli_query($conn,$sql);
if(!$FromWeek){
    die('<p class="warning">Query Error</p>'. mysqli_error($FromWeek));
}
$extraFromWeek = 0;
while($row=mysqli_fetch_array($FromWeek)){
    $extraFromWeek = floatval($row[0]);
}
if($extraFromWeek<0) { $extraFromWeek=0;}


//for garduate students
$sql ="select COUNT(DISTINCT gradstudent.ssn)*25 as gradStudentsPayment
from gradstudent
where supervisorSsn = '$currentSsn';";
$FromGrad = mysqli_query($conn,$sql);
if(!$FromGrad){
    die('<p class="warning">Query Error</p>'. mysqli_error($FromGrad));
}
$extraFromGrad = 0;
while($row=mysqli_fetch_array($FromGrad)){
    $extraFromGrad = floatval($row[0]);
}
if($extraFromGrad<0) { $extraFromGrad=0;}





//Days for the schedule table
$days = ["Monday","Tuesday","Wednesday","Thursday","Friday","Saturday","Sunday"];

mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="en">

    <head>

        <meta charset="UTF-8">
        <title><?php echo $info[0]?></title>
        <link rel="stylesheet" href="style.css">
        <style>
            .schedule td{
                padding: 0;
            }
            .schedule th{
                padding: 20px 40px;
            }
            .fs-08{
                font-size: 0.8rem;
            }
            .fs-08 th{
                font-size: 1rem;
            }

        </style>

    </head>
    <body>

        <nav class="d-block">
            <div class="container">
                <?php 
    echo "<span>".$info[0]."</span>";
            echo "<span>".$info[1]."</span>";
            echo "<span><a href='department-detail.php?dname=$info[2]'>".$info[2]."</a></span>";
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


            <!-- Free Hours Report-->
            <div class="table-box inline-block fs-08">
                <span class="table-name f-left">Free Hours Report for Students</span>
                <form class="lesson-form f-left" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
                    <select name="lesson" class="select-lesson">
                        <?php
                        foreach($les as $lesson){
                            if($lesson==$currentLesson){
                                echo "<option value='$lesson' selected>".$lesson."</option>";
                            }else {
                                echo "<option value='$lesson'>".$lesson."</option>";
                            }
                        }
                        ?>
                    </select>
                    <input type="hidden" name="ssn" value="<?php echo $currentSsn;?>">
                    <button type="submit" class="see-more" style="width:140px; padding: 11px 0;">Show&nbsp;Empty&nbsp;Hours</button>
                </form>

                <table cellspacing="0" class="schedule">

                    <tr>
                        <th></th>
                        <?php
                        for($i=1; $i<=13; $i++){
                            echo "<th class='schedule-numbers'>".$i."</th>";
                        }
                        ?>
                    </tr>

                    <?php
                    for($d = 0; $d<count($days); $d++){

                        echo "<tr> <th>$days[$d]</th>";

                        $i=1;
                        if($freeHours!==null){
                            foreach($freeHours as $row){

                                if($row[0]==$days[$d]){
                                    while($i<=13){
                                        if($i==$row[1]){
                                            echo "<td> 
                                <span class='d-block text-success'>Free</span>
                               </td>";
                                            $i++;
                                            break;
                                        }else{
                                            $i++;
                                            echo "<td> 
                                <span class='d-block text-danger'>Full</span>
                               </td>";
                                        }
                                    }
                                }
                            }
                            while($i<=13){
                                echo "<td> 
                                <span class='d-block text-danger'>Full</span>
                               </td>";
                                $i++;
                            }
                            echo "</tr>";
                        }
                    }
                    ?>
                </table>

            </div>


            <!-- Courses-->
            <div class="table-box inline-block">
                <span class="table-name"><?php echo $info[0];?>'s Current Courses</span>

                <table cellspacing="0">
                    <tr>
                        <th>Course&nbsp;Code</th>
                        <th>Course&nbsp;Name</th>
                        <th>ECTS</th>
                        <th>Weekly&nbsp;Hours</th>
                        <th>Prerequisite&nbsp;Course</th>
                    </tr>

                    <?php
                    while($row=mysqli_fetch_array($AllLesRes)){
                        echo "<tr>";

                        for($i=0; $i<mysqli_num_fields($AllLesRes); $i++){
                            if($i==mysqli_num_fields($AllLesRes)-1 && $row[$i]===null){  //checking if prereq course is null
                                echo "<td> None </td>";
                            } else {
                                echo "<td>".$row[$i]."</td>";
                            }    
                        }


                        echo "<td><a href='course-detail.php?courseCode=$row[0]'><div class='see-more'>More Detail</div></a></td>";
                        echo "</tr>";
                    }

                    ?>

                </table>
            </div>


            <!-- Leader Of Projects-->
            <div class="table-box inline-block">
                <span class="table-name">Projects Lead By <?php echo $info[0];?></span>

                <table cellspacing="0">
                    <tr>
                        <th>Leader&nbsp;Ssn</th>
                        <th>Project&nbsp;Name</th>
                        <th>Subject</th>
                        <th>Budget</th>
                        <th>Starting&nbsp;Date</th>
                        <th>Due&nbsp;Date</th>
                        <th>Controlling Department</th>
                    </tr>

                    <?php
                    if($resultset!==null){
                        foreach($resultset as $row){
                            echo "<tr>";

                            for($i=0; $i<$numOfFields; $i++){                  
                                echo "<td>".$row[$i]."</td>";                   
                            }
                            echo "<td><a href='project-detail.php?leadSsn=$row[0]&pName=$row[1]'><div class='see-more'>More Detail</div></a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>

                </table>
            </div>


            <!-- Projects-->
            <div class="table-box inline-block">
                <span class="table-name">Projects</span>


                <table cellspacing="0">

                    <tr>
                        <th>Leader&nbsp;SSn</th>
                        <th>Project&nbsp;Name</th>
                        <th>Workinh&nbsp;Hour</th>

                    </tr>

                    <?php
                    if($resultset2!==null){
                        foreach($resultset2 as $row2){
                            echo "<tr>";

                            for($i=0; $i<$numOfFields2; $i++){                  
                                echo "<td>".$row2[$i]."</td>";                   
                            }
                            echo "<td><a href='project-detail.php?leadSsn=$row2[0]&pName=$row2[1]'><div class='see-more'>More Detail</div></a></td>";
                            echo "</tr>";
                        }
                    }
                    ?>

                </table>
            </div>

            <!-- Adv. Students -->
            <div class="table-box inline-block mr-3">
                <span class="table-name">Students Whose Advisor is <?php echo $info[0];?></span>


                <table cellspacing="0">

                    <tr>
                        <th>SSN</th>
                        <th>Graduate or Under&nbsp;Graduate</th>
                        <th>Curriculum Code</th>
                        <th>Department</th>
                        <th>Student ID</th>
                        <th>Name</th>
                    </tr>

                    <?php
                    if($resultset3!==null){
                        foreach($resultset3 as $row){
                            echo "<tr>";

                            for($i=0; $i<$numOfFields3; $i++){  
                                if($i==1 && $row[$i]==1){
                                    echo "<td>Graduate</td>"; 
                                }else if($i==1 && $row[$i]==0){
                                    echo "<td>Under Graduate</td>"; 
                                }else{
                                    echo "<td>".$row[$i]."</td>";   
                                }
                            }
                    ?>
                    <td><a href="student-detail.php?ssn=<?php echo $row["ssn"]?>"><div class="see-more"style="padding: 12px;" >More Detail</div></a></td>
                    <?php
                        echo "</tr>";
                        }
                    }
                    ?>

                </table>
            </div>

            <!-- Salary-->
            <div class="table-box inline-block">
                <span class="table-name">Salary</span>

                <table cellspacing="0" class="short-table">

                    <tr>
                        <td>Base Salary</td>
                        <td><?php echo $baseSalary;?></td>
                    </tr>

                    <tr>
                        <td>Projects</td>
                        <td><?php echo $extraFromProject;?></td>
                    </tr>

                    <tr>
                        <td>Weekly Hours</td>
                        <td><?php echo $extraFromWeek;?></td>
                    </tr>

                    <tr>
                        <td>Graduate Students</td>
                        <td><?php echo $extraFromGrad;?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;">TOTAL</td>
                        <td><?php 
                            echo $baseSalary + $extraFromGrad +
                                $extraFromProject + $extraFromWeek;
                            ?>
                        </td>
                    </tr>

                </table>
            </div>
        </div>

    </body>

</html>
