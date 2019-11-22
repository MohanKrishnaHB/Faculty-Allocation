<?php
include('connection.php');
if(isset($_GET['fid'])) {
    $fid = $_GET['fid'];
    $sql = "DELETE FROM faculty WHERE fid='$fid';";
    if(!mysqli_query($conn, $sql)) {
        echo(mysqli_error($conn));
    }
    else {
        header('Location: /faculty_allocation/index.php');
    }
}
?>