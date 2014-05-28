<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων send the confirmation email
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */

require_once 'lib/phpmailer/class.phpmailer.php';

function mail_alt($email_from,$email_to,$email_subject,$email_txt,$fileatt,$fileatt_name){
	$ok=true;
	$mail = new PHPMailer(true); // the true param means it will throw exceptions on errors, which we need to catch
	$mail->CharSet = 'UTF-8';
	//$mail->IsSMTP(); // Uncomment to use SMTP
	if(!empty($email_to)){
		try {
			/* Uncomment and set up to SMTP
			//$mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
			$mail->SMTPAuth   = false;                  // enable SMTP authentication
			$mail->SMTPSecure = 'tls';
			$mail->Host       = "smtp_host"; 			// sets the SMTP server
			//$mail->Port       = 25;                    // set the SMTP port for the GMAIL server
			$mail->Username   = "smtp_username"; 		// SMTP account username
			$mail->Password   = "password";        		// SMTP account password
			*/
			$mail->AddAddress($email_to);
			$mail->SetFrom($email_from, EMAIL_FROM_ADDRESS);
			$mail->AddReplyTo($email_from, EMAIL_FROM_ADDRESS);
			$mail->Subject = $email_subject;
			$mail->MsgHTML($email_txt);
			$mail->AddAttachment($fileatt,$fileatt_name);      // attachment
			$mail->Send();
		} catch (phpmailerException $e) {
			echo $e->errorMessage(); //Pretty error messages from PHPMailer
			$ok=false;
		} catch (Exception $e) {
			echo $e->getMessage(); //Boring error messages from anything else!
			$ok=false;
		}
	}
	return $ok;
}

?>