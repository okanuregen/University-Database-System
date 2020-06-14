<?php
include("conn.php");
$currentDep = $_GET["dname"];


// Basic Dep info for nav
$sql = "SELECT D.dName, iname, ssn, buildingName, budget FROM  p1solution3.department D JOIN instructor I on D.headSsn = I.ssn WHERE D.dName='$currentDep';";
$info = mysqli_query($conn, $sql);

if(!$info){
    die("Query error". mysqli_error($info));
}
$info = mysqli_fetch_array($info);



//Instructors
$sql = "SELECT ssn,iname,rankk,basesal FROM p1solution3.instructor where dName = '$currentDep';";
$inst = mysqli_query($conn, $sql);

if(!$inst){
    die("Query error". mysqli_error($inst));
}
$instructors=null;
while ($row = mysqli_fetch_array($inst)) {
    $instructors[] = $row;
}


//Students
$sql ="SELECT S.ssn, S.studentid, S.studentname, S.gradorUgrad, I.iname, S.currCode, I.ssn FROM student S JOIN instructor I on S.advisorSsn = I.ssn WHERE S.dName='$currentDep';";
$stud = mysqli_query($conn, $sql);

if(!$stud){
    die("Query error". mysqli_error($stud));
}
$students=null;
while ($row = mysqli_fetch_array($stud)) {
    $students[] = $row;
}


//Projects
$sql="SELECT I.iname, P.pName, P.subject, P.budget, P.startDate, P.enddate, I.ssn FROM project P JOIN instructor I on P.leadSsn = I.ssn where P.controllingDName ='$currentDep';";
$proj = mysqli_query($conn, $sql);

if(!$stud){
    die("Query error". mysqli_error($proj));
}
$projects=null;
while ($row = mysqli_fetch_array($proj)) {
    $projects[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <title><?php echo $currentDep?></title>
        <link rel="stylesheet" href="style.css">
    </head>

    <body>

        <nav class="d-block">
            <div class="container">
                <?php 

    echo "<span>".$info[0]."</span>";
            echo "<span><a href='instructor-detail.php?ssn=$info[2]'>".$info[1]."</a></span>";
            echo "<span>".$info[3]."</span>";

            if($info[4]==null){
                echo "<span>Budget: 0</span>";
            } else{
                echo "<span>".$info[4]."</span>";
            }

                ?>
            </div>
        </nav>

        <div class="container">

            <!-- Instructors-->
            <div class="table-box inline-block mr-5">
                <span class="table-name">Instructor of <?php echo $info[0];?></span>

                <table cellspacing="0">
                    <tr>
                        <th>SSN</th>
                        <th>Name</th>
                        <th>Rank</th>
                        <th>Base&nbsp;Salary</th>
                    </tr>

                    <?php
                    if($instructors!==null){
                        foreach($instructors as $row){
                            echo "<tr>";

                            for($i=0; $i<mysqli_num_fields($inst); $i++){
                                if(($i==3 || $i==4) && $row[$i]===null){  //checking if salaries is null
                                    echo "<td> 0 </td>";
                                } else {
                                    echo "<td>".$row[$i]."</td>";
                                }    
                            }


                            echo "<td><a href='instructor-detail.php?ssn=$row[0]'><div class='see-more'>More Detail</div></a></td>";
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
                        <th>SSN</th>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Instructor</th>
                        <th>Curriculum</th>
                    </tr>

                    <?php
                    if($students!==null){
                        foreach($students as $row){
                            echo "<tr>";

                            for($i=0; $i<6; $i++){
                                if($i==3 && $row[$i]==0){  //checking grad or under
                                    echo "<td>Under Graduate </td>";
                                } else if ($i==3 && $row[$i]==1){
                                    echo "<td>Graduate </td>";
                                } else if($i==4){
                                    echo "<td><a href='instructor-detail.php?ssn=$row[6]' style=' color: #1f2fad;'>$row[$i]</div></a></td>";
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

            <!-- Projects -->
            <div class="table-box inline-block">
                <span class="table-name">Projects of <?php echo $info[0];?></span>

                <table cellspacing="0">
                    <tr>
                        <th>Instructor</th>
                        <th>Project Name</th>
                        <th>Subject</th>
                        <th>Budget</th>
                        <th>Starting Date</th>
                        <th>End Date</th>
                    </tr>

                    <?php
                    if($projects!==null){
                        foreach($projects as $row){
                            echo "<tr>";

                            for($i=0; $i<6; $i++){
                                if($i==3 && $row[$i]===null){  //checking if budget is null
                                    echo "<td>0</td>";
                                } else if($i==0){
                                    echo "<td><a href='instructor-detail.php?ssn=$row[6]' style=' color: #1f2fad;'>$row[$i]</div></a></td>";
                                }
                                else {
                                    echo "<td>".$row[$i]."</td>";
                                }

                            }


                            echo "<td><a href='project-detail.php?leadSsn=$row[6]&pName=$row[1]'><div class='see-more'>More Detail</div></a></td>";
                            echo "</tr>";
                        }
                    }

                    ?>

                </table>
            </div>



        </div>


    </body>

</html>
