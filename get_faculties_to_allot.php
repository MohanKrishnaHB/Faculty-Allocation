<?php
include 'connection.php';

if(isset($_GET['date']) && isset($_GET['session']) && !isset($_GET['auto_allot'])) {
    $given_date = $_GET['date'];
    $given_session = $_GET['session'];
    $sql = "SELECT DISTINCT(f.fid), f.name, f.department, f.duties, f.designation FROM faculty f LEFT JOIN faculty_duty fd ON f.fid=fd.fid WHERE f.fid NOT IN (SELECT fd1.fid FROM faculty_duty fd1 WHERE fd1.day_of_exam='$given_date') ORDER BY f.department;";
    echo('{"available": [');
    if($faculties_available = mysqli_query($conn, $sql)) {
        echo('{');
        while($faculty = mysqli_fetch_array($faculties_available)) {
            echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
        }
        echo('}');
    }
    echo('], "selected": [');
    $sql = "SELECT DISTINCT(f.fid), f.name, f.department, f.duties, f.designation FROM faculty f LEFT JOIN faculty_duty fd ON f.fid=fd.fid WHERE fd.day_of_exam='$given_date' and fd.session='$given_session' ORDER BY f.department;";
    if($faculties_available = mysqli_query($conn, $sql)) {
        echo('{');
        while($faculty = mysqli_fetch_array($faculties_available)) {
            echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
        }
        echo('}');
    }
    echo(']}');
}

if(isset($_GET['date']) && isset($_GET['session']) && isset($_GET['auto_allot'])) {
    $given_date = $_GET['date'];
    $given_session = $_GET['session'];
    $auto_allot = $_GET['auto_allot'];
    $auto_allot = explode("|", $auto_allot);
    $faculty_count = $auto_allot[0];
    $roomboy_count = $auto_allot[1];
    $sql = "SELECT DISTINCT(f.fid), f.name, f.department, f.duties, f.designation FROM faculty f LEFT JOIN faculty_duty fd ON f.fid=fd.fid WHERE f.fid NOT IN (SELECT fd1.fid FROM faculty_duty fd1 WHERE fd1.day_of_exam='$given_date') AND designation='Teaching' ORDER BY f.duties, f.department;";
    $sql1 = "SELECT DISTINCT(f.fid), f.name, f.department, f.duties, f.designation FROM faculty f LEFT JOIN faculty_duty fd ON f.fid=fd.fid WHERE f.fid NOT IN (SELECT fd1.fid FROM faculty_duty fd1 WHERE fd1.day_of_exam='$given_date') AND designation='Non-Teaching' ORDER BY f.duties, f.department;";

    echo('{"selected": [');
    $count = 0;
    echo('{');
    if($faculties_available = mysqli_query($conn, $sql)) {
        if(!$faculty_count==0) {
            while($faculty = mysqli_fetch_array($faculties_available)) {
                echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
                $count++;
                if($count==$faculty_count) {
                    break;
                }
            }
        }
    }
    $count1 = 0;
    if($roomboys_available = mysqli_query($conn, $sql1)) {
        if(!$roomboy_count==0) {
            while($faculty = mysqli_fetch_array($roomboys_available)) {
                echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
                $count1++;
                if($count1==$roomboy_count) {
                    break;
                }
            }
        }
    }
    echo('}');
    echo('], "available": [');
    echo('{');
    while($faculty = mysqli_fetch_array($faculties_available)) {
        echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
    }
    while($faculty = mysqli_fetch_array($roomboys_available)) {
        echo('"id": "'.$faculty[0].'", "name": "'.$faculty[1].'", "department": "'.$faculty[2].'", "duties": "'.$faculty[3].'", "designation": "'.$faculty[4].'"}, {');
    }
    echo('}');
    echo(']}');
}

?>