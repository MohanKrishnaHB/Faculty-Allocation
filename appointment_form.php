<?php
include("connection.php");
include('TCPDF/tcpdf.php');

$pdf=new TCPDF('P','mm','A4');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetTitle('Appoitment Form');
$pdf->SetMargins(20, 10, 10, true); // set the margins 


if(isset($_GET['fid'])) {
    $fid = $_GET['fid'];
    if($fid == 'All') {
        $sql = "SELECT fid, name, department, designation FROM faculty;";
    }
    else {
        $sql = "SELECT fid, name, department, designation FROM faculty WHERE fid='$fid';";
    }
    $faculties = mysqli_query($conn, $sql);
    $page_count = 0;
    while($faculty = mysqli_fetch_array($faculties)) {
        if($page_count%2==0) {
            $pdf->AddPage();
        }
        $pdf->setFont('Times','B',14);
        $pdf->WriteHTMLCell('','',45,'','<img src="mit_mysore.jpeg" height="40px" width="40px">',0,0,0,1,false,'L',false);
        $pdf->WriteHTMLCell('','',70-5,'','VTU EXAMINATION DEC-2019/JAN-2020',0,1,0,1,false,'L',false);
        $pdf->setFont('Times','B',10);
        $pdf->WriteHTMLCell('','',73-5,'',"MAHARAJA INSTITUTE OF TECHNOLOGY MYSORE",0,1,0,1,false,'C',false);

        $pdf->setFont('Times','',10);
        $pdf->ln();
        $pdf->WriteHTMLCell('','',46,'',"As per section 72 and 74 of the KSU act 2000 Examination duty is mandatory",0,1,0,1,false,'C',false);
        $pdf->ln();
        $pdf->WriteHTMLCell('','',25,'',"To, <br>".$faculty['name'].",<br>".$faculty['department']." dept.",0,1,0,1,false,'C',false);
        
        $pdf->setFont('Times','BU',10);
        if($faculty['designation']=='Non-Teaching') {
            $duty_title = 'ROOM BOY';
        }
        else {
            $duty_title = 'ROOM SUPERINTENTDENT';
        }
        $pdf->WriteHTMLCell('','','','',"APPOINTMENT OF $duty_title",0,1,0,1,true,'C',false);
        $pdf->ln();
        $pdf->setFont('Times','',10);
        if($faculty['designation']=='Non-Teaching') {
            $duty_title = 'Room Boy';
        }
        else {
            $duty_title = 'Room Superintendent';
        }
        $text = "You are hereby informed that you are appointed as <b>$duty_title</b> for VTU examinations DEC-2019/JAN-2020 at our College on the following dates and timings.";
        $pdf->WriteHTMLCell('','','','',$text,0,1,0,1,false,'C',false);
        $pdf->WriteHTMLCell(50,'',50,'',"<b>FN: 9.30am to 12.30pm</b>",0,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(50,'','','',"<b>AN: 2.00pm to 5.00pm</b>",0,1,0,1,true,'C',false);
        $pdf->ln();

        /* Header of table */
        $pdf->WriteHTMLCell(37,7,'','',"Date",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(20,7,'','',"Time",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(37,7,'','',"Date",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(20,7,'','',"Time",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(37,7,'','',"Date",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(20,7,'','',"Time",1,0,0,1,true,'C',false);

        /* Table body */
        $sql_for_duties = "SELECT day_of_exam, session FROM faculty_duty WHERE fid='$faculty[0]';";
        $duties = mysqli_query($conn, $sql_for_duties);
        $row_count = 0;
        while($duty = mysqli_fetch_array($duties)) {
            if($row_count%3==0) {
                $pdf->ln();
            }
            $pdf->WriteHTMLCell(37,7,'','',$duty[0],1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(20,7,'','',$duty[1],1,0,0,1,true,'C',false);
            $row_count++;
        }
        while($row_count!=9) {
            if($row_count%3==0) {
                $pdf->ln();
            }
            $pdf->WriteHTMLCell(37,7,'','',$duty[0],1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(20,7,'','',$duty[1],1,0,0,1,true,'C',false);
            $row_count++;
        }
        
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();

        $pdf->WriteHTMLCell('','',130,'',"Principal / Chief Superintendent",0,1,0,1,true,'C',false);


        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $pdf->ln();
        $page_count++;
    }
}
$pdf->output();
?>