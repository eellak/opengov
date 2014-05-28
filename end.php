<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων final step of submission
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
global $is_end;
$is_end =true;
require_once( 'global.php' );
require_once( 'receipt_data.php' );
require_once( 'lib/tcpdf/config/lang/gr.php' );
require_once( 'lib/tcpdf/tcpdf.php' );


initialize();
restrict();

if ( !isset( $_SESSION[ 'submission_id' ] ) ) {
	header( "Location: " . $config[ 'index' ] );
	
	exit();
}


?>
<div class="page-header"><h2><small>Η Αίτησή σας Καταχωρήθηκε</small></h2></div>
<?php

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

	$fileatt = $fname; // Path to the file
	$fileatt_type = "application/octet-stream"; // File Type
	$fileatt_name = $filename; // Filename that will be used for the file as the attachment

	$email_from = EMAIL_FROM_ADDRESS; // Who the email is from
	$email_subject = EMAIL_SUBJECT." #$protocol_id"; // The Subject of the email
	$email_txt = EMAIL_BODY; // Message that the email has in it

	$email_to = $row[ 'candidate_email' ]; // Who the email is to

	$headers .= "From: ".$email_from;

	$file = fopen($fileatt,'rb');
	$data = fread($file,filesize($fileatt));
	fclose($file);

	$semi_rand = md5(time());
	$mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

	$headers .= "\nMIME-Version: 1.0\n" .
	"Content-Type: multipart/mixed;\n" .
	" boundary=\"{$mime_boundary}\"";

	$email_message .= "This is a multi-part message in MIME format.\n\n" .
	"--{$mime_boundary}\n" .
	"Content-Type:text/html; charset=\"UTF-8\"\n" .
	"Content-Transfer-Encoding: 7bit\n\n" .
	$email_message . $email_txt."\n\n";

	$data = chunk_split(base64_encode($data));

	$email_message .= "--{$mime_boundary}\n" .
	"Content-Type: {$fileatt_type};\n" .
	" name=\"{$fileatt_name}\"\n" .
	"Content-Transfer-Encoding: base64\n\n" .
	$data . "\n\n" .
	"--{$mime_boundary}--\n";

	require_once( 'sendmail.php' );
	$ok = mail_alt($email_from,$email_to,$email_subject,$email_txt,$fileatt,$fileatt_name);

	echo '<center><a class="btn btn-primary"href="'.SITE_URL.'download_receipt.php?type=rec">Κατεβάστε αντίγραφο της αίτησης σε PDF</a><br /><br />';
	echo '<a href="'.SITE_URL.'" class="btn btn-info">Κλείσιμο και επιστροφή στην αρχική σελίδα</a></center><br />';

	@unlink( $fname );

	echo '<div class="well">'.$html.'</div>';
include('themes/footer.php'); 
?>