<?php
include ("conn.php");
$sql= "SELECT * FROM course order by courseName;";
$result = mysqli_query($conn,$sql);
if(!$result){
    die("Query error". mysqli_error($result));
}
$numOfFields = mysqli_num_fields($result);

$resultset = null;
while ($row = mysqli_fetch_array($result)) {
    $resultset[] = $row;
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Courses</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">


    </head>

    <body>
        <div class="container">
            <div class="table-box"> 
                <span class="table-name">All Courses</span>

                <table cellspacing="0">
                    <tr>
                        <th>Course&nbsp;Code</th>
                        <th>Course&nbsp;Name</th>
                        <th>ECTS</th>
                        <th>Weekly&nbsp;Hours</th>
                        <th>Prerequisite&nbsp;Course</th>
                    </tr>

                    <?php
                    foreach($resultset as $row){
                        echo "<tr>";

                        for($i=0; $i<$numOfFields; $i++){
                            if($i==$numOfFields-1 && $row[$i]===null){  //checking if prereq course is null
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
        </div>
    </body>

</html>
