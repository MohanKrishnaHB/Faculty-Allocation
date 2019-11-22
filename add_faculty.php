<?php
include 'connection.php';
$flag = 0;
if(isset($_POST['faculty_file_submit'])) {
    require "PHPExcel-1.8/Classes/PHPExcel/IOFactory.php";
    $inputfilename=$_FILES['faculty_file']['name'];
    $exceldata=array();

    try {
        $inputfiletype=PHPExcel_IOFactory::identify($inputfilename);
        $objreader=PHPExcel_IOFACTORY::createReader($inputfiletype);
        $objphpexcel=$objreader->load($inputfilename);
    }
    catch(Exception $e) {
        die('Error loading file "'.pathinfo($inputfilename,PATHINFO_BASENAME).'": '.$e->getMessage());
    }
    $sheet=$objphpexcel->getSheet(0);
    $highestrow=$sheet->getHighestRow();
    $highestcolumn=$sheet->getHighestColumn();
    for($row=1;$row<=$highestrow;$row++) {
        $rowdata=$sheet->rangeToArray('A'.$row.':'.$highestcolumn.$row,NULL,TRUE,FALSE);

        $sql="INSERT INTO faculty(fid, name, department, phone_number, designation) VALUES('".$rowdata[0][0]."','".$rowdata[0][1]."','".$rowdata[0][2]."','".$rowdata[0][3]."','".$rowdata[0][4]."');";
        if(mysqli_query($conn,$sql)) {
            //echo('inserted : '.$rowdata[0][1]);
        }
        else {
            $flag = 1;
            echo("Error : ".$sql."<br>".mysqli_error($conn)."<br>");
        }
    }
}
if(isset($_POST['faculty_form_submit'])) {
    $fid = $_POST['fid'];
    $name = $_POST['name'];
    $department = $_POST['department'];
    $phone = $_POST['phone'];
    $designation = $_POST['designation'];
    $sql="INSERT INTO faculty(fid, name, department, phone_number, designation) VALUES('$fid','$name','$department','$phone', '$designation');";
    if(mysqli_query($conn,$sql)) {
        //echo('inserted');
    }
    else {
        $flag = 1;
        echo("Error : ".$sql."<br>".mysqli_error($conn)."<br>");
    }
    
}
if($flag == 0) {
    header('Location: /faculty_allocation/index.php');
}
?>