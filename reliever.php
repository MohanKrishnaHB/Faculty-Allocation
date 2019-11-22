<?php
include('connection.php');

$fid = $_GET['fid'];
$date = $_GET['date'];
$reliever = $_GET['reliever'];

$sql = "UPDATE faculty_duty SET reliever='$reliever' WHERE fid='$fid' AND day_of_exam='$date';";
$res = mysqli_query($conn, $sql);

$sql = "SELECT reliever FROM faculty_duty WHERE fid='$fid' AND day_of_exam='$date';";
$res = mysqli_query($conn, $sql);
$res = mysqli_fetch_array($res);
echo($res[0]);
?>