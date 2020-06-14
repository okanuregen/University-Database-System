<?php
include ( 'conn.php' );
$sql = 'SELECT ssn, studentid, studentname, gradorUgrad, dName, currCode, advisorSsn FROM student order by ssn;';
$result = mysqli_query( $conn, $sql );
if ( !$result ) {
    die( 'Query error'. mysqli_error( $result ) );
}
$numOfFields = mysqli_num_fields( $result );

$resultset = null;
while ( $row = mysqli_fetch_array( $result ) ) {
    $resultset[] = $row;
}



mysqli_close( $conn );
?>

<!DOCTYPE html>
<html lang='en'>

    <head>
        <meta charset='UTF-8'>
        <meta name='viewport' content='width=device-width, initial-scale=1.0'>
        <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
        <title>Students</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <div class="table-box">
                <span class='table-name'>All Students</span>

                <table cellspacing='0'>
                    <tr>
                        <th>SSN</th>
                        <th>Student&nbsp;ID</th>
                        <th>Student&nbsp;Name</th>
                        <th>Status</th>
                        <th>Department&nbsp;Name</th>
                        <th>Curriculum</th>
                        <th>Advisor&nbsp;SSN</th>
                    </tr>

                    <?php
                    foreach($resultset as $row){
                        echo "<tr>";

                        for($i=0; $i<$numOfFields; $i++){
                            if($i==3 && $row[$i]==0){
                                echo "<td> Under Graduate</td>";
                            }
                            else if($i==3 && $row[$i]==1){
                                echo "<td>Graduate</td>";
                            }else if($i==6){
                                    echo "<td><a href='instructor-detail.php?ssn=$row[$i]' style=' color: #1f2fad;'>$row[$i]</div></a></td>";
                                }
                            else {
                                echo "<td>".$row[$i]."</td>";
                            }
                        }
                    ?>
                    <td><a href="student-detail.php?ssn=<?php echo $row["ssn"];?>">
                        <div class="see-more"> See More </div>
                        </a></td>
                    <?php
                        echo "</tr>";
                    }

                    ?>

                </table>
            </div>
        </div>
    </body>

</html>
