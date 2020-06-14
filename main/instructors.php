<?php
include ("conn.php");
$sql= "SELECT * FROM instructor order by ssn;";
$result = mysqli_query($conn,$sql);
if(!$result){
    die("Query error". mysqli_error($schedule));
}
$numOfFields = mysqli_num_fields($result);

$resultset = array();
while ($row = mysqli_fetch_array($result)) {
    $resultset[] = $row;
}

$colNames = "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'p1solution3' AND TABLE_NAME = 'instructor';";
$result2 = mysqli_query($conn,$colNames);
if(!$result2){
    die('<p class="warning">Query Error</p>'. mysqli_error($result2));
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>Instructors</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
       
    </head>

    <body>
        <div class="container">
            <div class="table-box"> 
                <span class="table-name">All Instructors</span>

                <table cellspacing="0">

                    <tr>
                        <?php
                        while($row=mysqli_fetch_array($result2)){
                            echo "<th>".$row[0]."</th>";
                        }

                        ?>
                    </tr>

                    <?php
                    foreach($resultset as $row){
                        echo "<tr>";

                        for($i=0; $i<$numOfFields; $i++){
                            if($i==$numOfFields-1 && $row[$i]===null){
                                echo "<td> 0 </td>";
                            }else {
                                echo "<td>".$row[$i]."</td>";
                            }
                        }
                    ?>
                    <td><a href="instructor-detail.php?ssn=<?php echo $row["ssn"];?>">
                        <div class="see-more">See More</div></a></td>
                    <?php
                        echo "</tr>";
                    }

                    ?>

                </table>
            </div>
        </div>
    </body>

</html>
