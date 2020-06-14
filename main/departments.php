<?php
include ("conn.php");
$sql= "SELECT * FROM department order by dName;";
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
        <title>Departments</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
        
       
    </head>

    <body>
        <div class="container">
            <div class="table-box"> 
                <span class="table-name">All Departments</span>

                <table cellspacing="0">

                    <tr>
                     <th>DepartmentName</th>
                     <th>Budget</th>
                     <th>Header Ssn</th>
                     <th>Building Name</th>
                        
                    </tr>

                    <?php
                    foreach($resultset as $row){
                        echo "<tr>";

                        for($i=0; $i<$numOfFields; $i++){
                            if($i==1 && $row[$i]===null){
                                echo "<td> 0 </td>";
                            }else {
                                echo "<td>".$row[$i]."</td>";
                            }
                        }
                    ?>
                    <td><a href="department-detail.php?dname=<?php echo $row[0];?>">
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
