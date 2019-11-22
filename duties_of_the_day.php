<?php
include("connection.php");
include('TCPDF/tcpdf.php');

$pdf=new TCPDF('P','mm','A4');
$pdf->setPrintHeader(false);
$pdf->setPrintFooter(false);

$pdf->SetTitle('Appoitment Form');
$pdf->SetMargins(5, 10, 10, true); // set the margins 


if(isset($_GET['date'])) {
    $date = $_GET['date'];
    $session = $_GET['session'];
    $sql = "SELECT f.fid, name, department FROM faculty f, faculty_duty fd WHERE f.fid=fd.fid AND day_of_exam='$date' AND session='$session' AND f.designation='Teaching';";
    $faculties = mysqli_query($conn, $sql);
    $pdf->AddPage();
    
    $pdf->setFont('Times','B',14);
    $pdf->WriteHTMLCell('','','','',$date.' | '.$session,0,1,0,1,true,'C',false);
    $pdf->ln();
    $pdf->WriteHTMLCell('','','','',"ROOM SUPERINTENDENT",0,1,0,1,true,'C',false);
    $pdf->setFont('Times','',12);
    /* Header of table */
    $pdf->WriteHTMLCell(25,7,'','',"FID",1,0,0,1,true,'C',false);
    $pdf->WriteHTMLCell(50,7,'','',"NAME",1,0,0,1,true,'C',false);
    $pdf->WriteHTMLCell(35,7,'','',"DEPARTMENT",1,0,0,1,true,'C',false);
    $pdf->WriteHTMLCell(35,7,'','',"ROOM NO",1,0,0,1,true,'C',false);
    $pdf->WriteHTMLCell(55,7,'','',"SIGNATURE",1,1,0,1,true,'C',false);
    
    /* Table body */
    while($faculty = mysqli_fetch_array($faculties)) {
        $pdf->WriteHTMLCell(25,7,'','',$faculty[0],1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(50,7,'','',$faculty[1],1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(35,7,'','',$faculty[2],1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(35,7,'','',"",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(55,7,'','',"",1,1,0,1,true,'C',false);

    }
    $pdf->ln();
    $sql = "SELECT f.fid, name, department FROM faculty f, faculty_duty fd WHERE f.fid=fd.fid AND day_of_exam='$date' AND session='$session' AND f.designation='Non-Teaching';";
    $faculties = mysqli_query($conn, $sql);
    if(mysqli_num_rows($faculties)>0) {
        $pdf->setFont('Times','B',14);
        $pdf->WriteHTMLCell('','','','',"ROOM BOY",0,1,0,1,true,'C',false);
        $pdf->setFont('Times','',12);
        /* Header of table */
        $pdf->WriteHTMLCell(25,7,'','',"FID",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(50,7,'','',"NAME",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(35,7,'','',"DEPARTMENT",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(35,7,'','',"ROOM NO",1,0,0,1,true,'C',false);
        $pdf->WriteHTMLCell(55,7,'','',"SIGNATURE",1,1,0,1,true,'C',false);
        
        /* Table body */
        while($faculty = mysqli_fetch_array($faculties)) {
            $pdf->WriteHTMLCell(25,7,'','',$faculty[0],1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(50,7,'','',$faculty[1],1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(35,7,'','',$faculty[2],1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(35,7,'','',"",1,0,0,1,true,'C',false);
            $pdf->WriteHTMLCell(55,7,'','',"",1,1,0,1,true,'C',false);

        }
    }
    
}
$pdf->output();
?>