<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων receipt functions
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
ob_start();
global $is_pdf;
$is_pdf = true;
require_once( 'global.php' );
require_once( 'receipt_data.php' );
require_once( 'lib/tcpdf/config/lang/gr.php' );
require_once( 'lib/tcpdf/tcpdf.php' );
initialize();
//enableErrorHandler();
restrict();
if ( !isset( $_SESSION[ 'submission_id' ] )) 
{
        header( "Location: " . $config[ 'index' ] );
        exit();
}

	$id = $_SESSION[ 'submission_id' ]; 
	$html = getreporthtml($id);
	
$fname =  tempnam("/tmp", "php_pdf_");
setlocale( LC_ALL, "en_US.UTF-8" ); //For making tcpdf work
$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetTitle('Υποβολή Αίτησης');
$pdf->SetHeaderData('',0, 'Υποβολή Αίτησης', '');
$pdf->setHeaderFont(Array('dejavusans', '', 10));
$pdf->setFooterFont(Array('dejavusans', '', 10));
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
$pdf->setLanguageArray($l);
$pdf->SetFont('dejavusans', '', 10);
$pdf->AddPage();
$pdf->writeHTML($html, true, 0, true, 0);
$pdf->lastPage();
$pdf->Output( $fname, 'F');
setlocale( LC_ALL, "el_GR.UTF-8" );
//Close and output PDF document
$id = $_SESSION[ 'submission_id' ];

$_SESSION[ 'pdf_file' ] = $fname;
$res = mysql_query( "SELECT * FROM submissions WHERE submission_id='$id'" );
$row = mysql_fetch_assoc( $res );

$protocol_id=$row[ 'submission_protocol' ]; 

$fp = fopen($fname, 'r');
$file_size=filesize($fname);
$file_content = fread($fp, $file_size) or die("Error: cannot read file1");
$content = mysql_real_escape_string($file_content) or die("Error: cannot read file2");
fclose($fp);		
$filename="receipt_".$protocol_id.".pdf";


$fp = fopen($fname, 'r');
$file_size=filesize($fname);
$file_content = fread($fp, $file_size) or die("Error: cannot read file1");
$content = mysql_real_escape_string($file_content) or die("Error: cannot read file2");
fclose($fp);
$filename="receipt_".$protocol_id.".pdf";

header( "Content-type: application/octet-stream" );
header( "Content-Disposition: attachment; filename=\"$filename\"" );
header( "Content-Length: " . $file_size );
print $file_content;
flush();
unlink($fname);
exit();
?>