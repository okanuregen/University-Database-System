<?php
$dbname ="new_university";
//$conn=mysqli_connect("localhost", "root","");
$conn=mysqli_connect("your_server", "your_username","your_password");

if(!$conn){
    die("unsuccesful connection".mysqli_error($conn));
}

$b = mysqli_select_db($conn, $dbname);
if(!$b){
    die("db couldn't retrieved ".mysqli_error($conn));
}
$conn -> Set_charset("utf8");
?>
