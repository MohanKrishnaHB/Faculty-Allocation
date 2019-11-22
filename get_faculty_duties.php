<?php
include 'connection.php';

if(isset($_GET['fid'])) {
    $fid = $_GET['fid'];
    $sql = "SELECT day_of_exam, session, reliever FROM faculty_duty WHERE fid='$fid';";
    echo('{"duties": [{');
    if($res = mysqli_query($conn, $sql)) {
        while($row = mysqli_fetch_array($res)) {
            echo('"date": "'.$row[0].'", "session": "'.$row[1].'", "reliever": "'. $row[2] .'"}, {');
        }
    }
    echo('}], ');

    $sql = "SELECT fid, name, department, phone_number FROM faculty WHERE fid='$fid'";
    $res = mysqli_query($conn, $sql);
    $row = mysqli_fetch_array($res);
    echo('"fid": "'. $row[0] .'", "name": "'.$row[1].'", "department": "'. $row[2] .'", "phone": "'. $row[3] .'"}');
}
?>