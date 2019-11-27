<?php
include 'connection.php';

$date = $_GET['date'];
$session = $_GET['session'];
$sql = "DELETE FROM faculty_duty WHERE day_of_exam='$date' AND session='$session';";
mysqli_query($conn, $sql);

$sql = "SELECT fid FROM faculty;";
$faculties = mysqli_query($conn, $sql);

while($faculty = mysqli_fetch_array($faculties)) {
echo($faculty[0]);
    $sql1 = "SELECT COUNT(*) FROM faculty_duty WHERE fid='$faculty[0]';";
    $res1 = mysqli_query($conn, $sql1);
    $count = mysqli_fetch_array($res1);
    echo("-->$count[0]\n");
    $sql1 = "UPDATE faculty SET duties='$count[0]' WHERE fid='$faculty[0]';";
    mysqli_query($conn, $sql1);
}
header('Location: index.php');
?>
