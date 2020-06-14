<?php
include ( 'conn.php' );
$currentLeadSsn = $_GET['leadSsn'];
$currentPrName = $_GET['pName'];

//Info
$sql="SELECT P.pName, I.iname, P.controllingDName, P.budget, I.ssn FROM project P, instructor I WHERE leadSsn=i.ssn and pName='$currentPrName' and leadSsn='$currentLeadSsn';";

$info = mysqli_query($conn, $sql);
if ( !$info ) {
    die( 'Query error'. mysqli_error( $info ) );
}
$info = mysqli_fetch_array( $info );

//Students in Project
$sql="SELECT PG.pName, S.studentname, PG.Gradssn, PG.workingHour FROM project_has_gradstudent PG join student S on S.ssn=PG.Gradssn WHERE PG.pName='$currentPrName';";

$sip = mysqli_query($conn,$sql);
if(!$sip){
    die('Query error'.mysqli_error($sip));
}
$resultset = null;
$numOfFields = mysqli_num_fields( $sip );
while ( $row = mysqli_fetch_array( $sip ) ) {
    $resultset[] = $row;
}

//Instructors in Project
$sql="SELECT PI.pName,I.iname,PI.issn,PI.workinghour FROM p1solution3.project_has_instructor PI join instructor I on PI.issn=I.ssn WHERE PI.pName='$currentPrName';";

$iip = mysqli_query($conn,$sql);
if(!$sip){
    die('Query error'.mysqli_error($iip));
}
$resultset1 = null;
$numOfFields1 = mysqli_num_fields( $iip );
while ( $row1 = mysqli_fetch_array( $iip ) ) {
    $resultset1[] = $row1;
}

?>

<!DOCTYPE html>
<html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <title><?php echo $info[0]?></title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    </head>


    <body>
        <nav class="d-block">
            <div class="container">
                <?php 
                echo "<span>".$info[0]."</span>";
                echo "<span><a href='instructor-detail.php?ssn=$info[4]'>".$info[1]."</a></span>";
                echo "<span><a href='department-detail.php?dname=$info[2]'>".$info[2]."</a></span>";
                echo "<span class='text-capitalize'>Budget: ".$info[3]."</span>";
                ?>

            </div>
        </nav>

        <div class="container">
            <div class="table-box inline-block mr-5">
                <span class='table-name'>Students in Project</span>
                <table cellspacing='0'>
                    <tr>

                        <th>Project Name</th>
                        <th>Student Name</th>
                        <th>Student Ssn</th>
                        <th>Working Hour</th>

                    </tr>
                    <?php
                    if($resultset!==null){
                        foreach($resultset as $row){
                            echo '<tr>';

                            for($i=0;$i<$numOfFields;$i++){
                                echo '<td>'.$row[$i].'</td>';
                            }
                            echo "<td><a href='student-detail.php?ssn=$row[2]'><div class='see-more'>More Detail</div></a></td>";
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>

            <div class="table-box inline-block">
                <span class='table-name'>Instructors in Project</span>
                <table cellspacing='0'>

                    <tr>

                        <th>Project Name</th>
                        <th>Instructor Name</th>
                        <th>Instructor Ssn</th>
                        <th>Working Hour</th>

                    </tr>
                    <?php
                    if($resultset1!==null){
                        foreach($resultset1 as $row){
                            echo '<tr>';

                            for($i=0;$i<$numOfFields1;$i++){
                                echo '<td>'.$row[$i].'</td>';
                            }
                            echo "<td><a href='instructor-detail.php?ssn=$row[2]'><div class='see-more'>More Detail</div></a></td>";
                            echo '</tr>';
                        }
                    }
                    ?>
                </table>
            </div>
        </div>

    </body>










</html>
