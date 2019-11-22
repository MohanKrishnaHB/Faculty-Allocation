<?php
include 'connection.php';

if(isset($_POST['allot'])) {
    $date = $_POST['date'];
    $session = $_POST['session'];
    $faculties_selected_temp = $_POST['faculties_selected'];
    $faculties_selected = explode("|", $faculties_selected_temp);
    
    $sql = "DELETE FROM faculty_duty WHERE day_of_exam='$date' AND session='$session';";
    mysqli_query($conn, $sql);

    foreach ($faculties_selected as $fid) {
        $sql = "INSERT INTO faculty_duty(fid, day_of_exam, session) VALUES('$fid', '$date', '$session');";
        mysqli_query($conn, $sql);

        $sql = "SELECT COUNT(*) FROM faculty_duty WHERE fid='$fid';";
        $res = mysqli_query($conn, $sql);
        $count = mysqli_fetch_array($res);
        $sql = "UPDATE faculty SET duties='$count[0]' WHERE fid='$fid';";
        mysqli_query($conn, $sql);

    }
    header('Location: index.php');
}
?>