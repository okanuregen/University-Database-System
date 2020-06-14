<?php
include ( 'conn.php' );
$sql = 'SELECT * FROM project order by pName;';
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
        <title>Projects</title>
        <link rel="stylesheet" href="style.css">
        <link href="https://fonts.googleapis.com/css2?family=Nanum+Gothic:wght@700&display=swap" rel="stylesheet">
    </head>

    <body>
        <div class="container">
            <div class="table-box">
                <span class='table-name'>All Projects</span>
                <table cellspacing='0'>

                    <tr>
                        <th>Leader&nbsp;Ssn</th>
                        <th>Project&nbsp;Name</th>
                        <th>Subject</th>
                        <th>Budget</th>
                        <th>Starting&nbsp;Date</th>
                        <th>End&nbsp;Date</th>
                        <th>Controlling&nbsp;Departments</th>
                    </tr>

                    <?php
                    if($resultset!==null){
                        foreach($resultset as $row){
                            echo "<tr>";

                            for($i=0; $i<$numOfFields; $i++){
                                echo "<td>".$row[$i]."</td>";

                            }


                            echo "<td><a href='project-detail.php?leadSsn=$row[0]&pName=$row[1]'><div class='see-more'>More Detail</div></a></td>";
                            echo '</tr>';

                        }
                    }
                    ?>

                </table>
            </div>
        </div>
    </body>

</html>
