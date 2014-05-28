<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων function for receipt
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
require_once( 'global.php' );
initialize();
 

function hh( $msg ) { 
	$msg = trim( $msg );
	$msg = htmlspecialchars( $msg );
	$msg = str_replace( "\n", "<br>", $msg );
	return $msg;
}
function  getreporthtml($id)
{
	$html = '';
	$positions = array();
    $res = mysql_query( "SELECT * FROM positions" );
	while ( ( $row = mysql_fetch_assoc( $res ) ) !== false ) {
		$positions[ $row[ 'office_id' ] ] = replace_gram( $row[ 'office_name' ] );
	}

	$res = mysql_query( "SELECT * FROM submissions WHERE submission_id='$id'" );
	$row = mysql_fetch_assoc( $res );

	$protocol_id = $row[ 'submission_protocol' ];
	$html .= "<p>Έχετε υποβάλει αίτηση με αριθμό <b>#$protocol_id</b> για την ακόλουθη θέση</p>";
	$html .= "<ol>";
	for ( $i = 1; $i <= 5; $i++ ) {
		$cid = "gen$i" . "_id";
		if ( $row[ $cid ] >= 0 ) {
			$html .= "<li>" . hh( $positions[ $row[ $cid ] ] ) . "</li>";
		}
	}
	$html .= "</ol>";

	$timestamp = $row[ 'submission_date' ];

	$html .= "<p>Στοιχεία Αίτησης</p>";
	$html .= "<hr>";

	$html .= "<h3>Προσωπικά Στοιχεία:</h3><br>";
	$html .=  "<b>Όνομα:</b> " . hh( $row[ 'candidate_name' ] ) . "<br>";
	$html .=  "<b>Επώνυμο:</b> " . hh( $row[ 'candidate_surname' ] ) . "<br>";
	$html .=  "<b>Όνομα Πατρός:</b> " . hh( $row[ 'candidate_fathername' ] ) . "<br>";
	$html .=  "<b>Ημ/νια Γέννησης:</b> " . hh( $row[ 'candidate_birth_day' ] ) . '/' . hh( $row[ 'candidate_birth_month' ] ) . '/' . hh( $row[ 'candidate_birth_year' ] ) . "<br>";
	$html .= "<b>Οικογενειακή κατάσταση:</b> " . hh( $row[ 'candidate_marital_status' ] ) . "<br>";
	$html .= "<b>Αριθμός τέκνων αν υπάρχουν:</b> " . hh( $row[ 'candidate_no_of_children' ] ) . "<br>";
	$html .= "<b>Φύλο:</b> " . hh( $row[ 'candidate_gender' ] ) . "<br>";
	$html .= "<b>Επαγγελματική κατηγορία / Επάγγελμα:</b> " . hh( $row[ 'candidate_vocation' ] ) . "<br>";
	$html .= "<b>Τρέχουσα Απασχόληση:</b> " . hh( $row[ 'candidate_occupation_type' ] ) . "<br>";
	$html .= "<b>Υπηκοότητα:</b> " . hh( $row[ 'candidate_nationality' ] ) . "<br>";
	$html .= "<b>Διεύθυνση:</b> ";
	$html .= hh( $row[ 'candidate_address_road' ] ) . " " . hh( $row[ 'candidate_address_number' ] ) . ", " . hh( $row[ 'candidate_address_areacode' ] ) . ", ";
	$html .= hh( $row[ 'candidate_address_city' ] ) . ", " . hh( $row[ 'candidate_address_country'] ) . "<br>";
    
	$kapquery="SELECT * FROM kapodistrias WHERE lgoid='".mysql_escape_string($row[ 'candidate_address_lgoid' ])."'";	
	$kapres = mysql_query( $kapquery);
	$kaprow = mysql_fetch_assoc( $kapres );

	$html .= "<b>Περιφέρεια:</b> " . hh( $kaprow[ 'region' ] ) . "<br>";
	$html .= "<b>Δήμος ή κοινότητα:</b> " . hh( $kaprow[ 'lgotype' ] ) ." ". hh( $kaprow[ 'lgo' ] ) . "<br>";
	$html .= "<b>Email:</b> " . hh( $row[ 'candidate_email' ] ) . "<br>";
	$html .= "<b>Τηλέφωνο:</b> " . hh( $row[ 'candidate_tel' ] ) . "<br>";
	$html .= "<b>Κινητό:</b> " . hh( $row[ 'candidate_mobile' ] ) . "<br>";
	$html .= "<b>Διεύθυνση προσωπικής ιστοσελίδας / blog:</b> " . hh( $row[ 'candidate_homepage' ] ) . "<br>";
	$html .= "<b>Η διαθεσιμότητα απασχόλησης είναι πλήρης;</b> " . hh( $row[ 'candidate_work_fulltime' ] ) . "<br>";
	if  ($row[ 'candidate_work_fulltime' ]>0) {
		$html .= "<b>Αριθμός αίτησής πρόσκλησης που αφορούσε τους Γενικούς και Ειδικούς Γραμματείς</b> " . hh( $row[ 'candidate_gg_protocol_id' ] ) . "<br>";
	}
	
	$html .= "<h3>Επαγγελματική Εμπειρία:</h3><br>";
	$html .= "<b>Διοικητική εμπειρία σε θέση ευθύνης του Ιδιωτικού Τομέα, (χρόνια):</b><br>";
	$html .= hh( $row[ 'candidate_workexp_privatesector' ] );	
	$html .= "<br>";
	$html .= "<b>Διοικητική εμπειρία σε θέση ευθύνης του Δημόσιου Τομέα, (χρόνια):</b><br>";
	$html .= hh( $row[ 'candidate_workexp_publicsector' ] );	
	$html .= "<br>";
	$html .= "<br><b>Επαγγελματική πείρα:</b> " . "<br>";
	$res = mysql_query( "SELECT * FROM work WHERE submission_id='$id' ORDER BY period_from" );
	
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'period_from' ].$row_array[ 'period_to' ].$row_array[ 'position' ].$row_array[ 'activities' ].$row_array[ 'employer_name' ]=='')) {
			$html .= "<br><b>Από:</b> " . hh( $row_array[ 'period_from' ] ) . "<br>";
			$html .= "<b>Εώς:</b> " . hh( $row_array[ 'period_to' ] ) . "<br>";	
			$html .= "<b>Απασχόληση ή θέση που κατείχατε:</b> " . hh( $row_array[ 'position' ] ) . "<br>";
			$html .= "<b>Κύριες δραστηριότητες και αρμοδιότητες:</b> " . hh( $row_array[ 'activities' ] ) . "<br>";	
			$html .= "<b>Επωνυμία εργοδότη:</b> " . hh( $row_array[ 'employer_name' ] )  . "<br>";
			$html .= "<b>Πόλη εργοδότη:</b> "	. hh( $row_array[ 'employer_city' ] ) . "<br>";
			$html .= "<b>T.K. εργοδότη:</b> " . hh( $row_array[ 'employer_postcode' ] ) . "<br>";
			$html .= "<b>Είδος της επιχείρησης ή του κλάδου:</b> " . hh( $row_array[ 'employer_type' ] ) . "<br>";
			$html .= "<br>";
		}
	}	
	$html .= "<b>Στοιχεία για την επαγγελματική σας δραστηριότητα:</b><br><br>";
	$html .= hh( $row[ 'candidate_prof' ] );	
	$html .= "<br>";

	$html .= "<h3>Σπουδές:</h3><br>";
	$html .= "<b>Έτος αποφοίτησης απο το Λύκειο:</b> ";
	$html .= hh( $row[ 'candidate_lykeio_year' ] );	
	$html .= "<br><br>";
	$html .= "<b>Εκπαίδευση και Κατάρτιση:</b> ";
	$html .= hh( $row[ 'candidate_edu_level' ] );	
	$html .= "<br>";
	$html .= "<br><b>Προπτυχιακές σπουδές:</b><br>";
	$res = mysql_query( "SELECT * FROM education_ba WHERE submission_id='$id' ORDER BY year_from" );
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'name' ].$row_array[ 'name_other' ].$row_array[ 'department' ].$row_array[ 'comments' ]=='')) {
			$html .= "<br><b>Χώρα σπουδών:</b> " . hh( $row_array[ 'country' ] ) . "<br>";
			$html .= "<b>Εκπαιδευτικό ίδρυμα ΑΕΙ - ΤΕΙ:</b> " . hh( $row_array[ 'name' ] ) . "<br>";
			$html .= "<b>Εκπαιδευτικό ίδρυμα άλλης χώρας:</b> " . hh( $row_array[ 'name_other' ] ) . "<br>";
			$html .= "<b>Τμήμα σπουδών:</b> " . hh( $row_array[ 'department' ] ) . "<br>";
			$html .= "<b>Έτος εισαγωγής:</b> " . hh( $row_array[ 'year_from' ] ) . "<br>";
			$html .= "<b>Έτος αποφοίτησης:</b> " . hh( $row_array[ 'year_to' ] ) . "<br>";
			$html .= "<b>Βαθμός αποφοίτησης:</b> " . hh( $row_array[ 'final_grade' ] ) . "<br>";
			$html .= "<b>Συμπληρωματικά στοιχεία:</b> " . hh( $row_array[ 'comments' ] ) . "<br>";
			$html .= "<br>";
		}
	}		
	$html .= "<br>";
	$html .= "<br><b>Μεταπτυχιακές σπουδές:</b><br>";
	$res = mysql_query( "SELECT * FROM education_further WHERE submission_id='$id' ORDER BY year_from" );
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'name' ].$row_array[ 'name_other' ].$row_array[ 'department' ].$row_array[ 'comments' ]=='')) {
			$html .= "<br><b>Χώρα σπουδών:</b> " . hh( $row_array[ 'country' ] ) . "<br>";
			$html .= "<b>Εκπαιδευτικό ίδρυμα ΑΕΙ - ΤΕΙ:</b> " . hh( $row_array[ 'name' ] ) . "<br>";
			$html .= "<b>Εκπαιδευτικό ίδρυμα άλλης χώρας:</b> " . hh( $row_array[ 'name_other' ] ) . "<br>";
			$html .= "<b>Επίπεδο σπουδών:</b> " . hh( $row_array[ 'title' ] ) . "<br>";
			$html .= "<b>Τμήμα σπουδών:</b> " . hh( $row_array[ 'department' ] ) . "<br>";
			$html .= "<b>Έτος εισαγωγής:</b> " . hh( $row_array[ 'year_from' ] ) . "<br>";
			$html .= "<b>Έτος αποφοίτησης:</b> " . hh( $row_array[ 'year_to' ] ) . "<br>";
			$html .= "<b>Βαθμός αποφοίτησης:</b> " . hh( $row_array[ 'final_grade' ] ) . "<br>";
			$html .= "<b>Συμπληρωματικά στοιχεία:</b> " . hh( $row_array[ 'comments' ] ) . "<br>";
			$html .= "<br><br>";
		}
	}		
	
	/*$html .= "<br><b>Δημοσιεύσεις:</b><br>";
	$html .= hh( $row[ 'candidate_edu_publications' ] );	
	$html .= "<br>";*/
	$html .= "<b>Στοιχεία για τις σπουδές σας:</b><br>";
	$html .= hh( $row[ 'candidate_edu' ] );	
	$html .= "<br>";
	
	$html .= "<h3>Γλώσσες:</h3><br>";
	$html .= "<b>Μητρική γλώσσα:</b> ";
	$html .= hh( $row[ 'candidate_lang_mother1' ] );	
	$html .= "<br>";
	$html .= "<b>Δεύτερη μητρική γλώσσα:</b> ";
	$html .= hh( $row[ 'candidate_lang_mother2' ] );	
	$html .= "<br>";
	$res = mysql_query( "SELECT * FROM languages_simple WHERE submission_id='$id' ORDER BY id" );
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'language' ]=='')) {
			$html .= "<br><b>Γλώσσα:</b> " . hh( $row_array[ 'language' ] ) . "<br>";
			$html .= "<b>Ικανότητα χρήσης:</b> " . hh( $row_array[ 'level' ] ) . "<br>";	
			$html .= "<br>";
		}
	}		
	
	$html .= "<h3>Γνώσεις ΤΠΕ:</h3><br>";
	$res = mysql_query( "SELECT * FROM computers WHERE submission_id='$id'" );
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'word_processing' ]=='')) {  
			$html .= "<b>Επεξεργασία κειμένου:</b> " . hh( $row_array[ 'word_processing' ] ) . " " . hh( $row_array[ 'name'] ) . "<br>";
			$html .= "<b>Βάσεις δεδομένων:</b> " . hh( $row_array[ 'db_knowledge' ] ) . "<br>";	
			$html .= "<b>Υπολογιστικά φύλα:</b> "	. hh( $row_array[ 'spreadsheets' ] ) . "<br>";
			$html .= "<b>Δίκτυα:</b> " . hh( $row_array[ 'networks' ] ) . "<br>";
			$html .= "<b>Εργαλεία internet:</b> " . hh( $row_array[ 'internet' ] ) . "<br>";
			$html .= "<br>";
		}
	}	
	$html .= hh( $row[ 'computer_exp' ] );	
	$html .= "<br>";


	$html .= "<h3>Κοινωνική Δραστηριότητα:</h3><br>";
	$html .= hh( $row[ 'candidate_social_activity' ] );	
	$html .= "<h3>Διοικητική εμπειρία:</h3><br>";
	$html .= hh( $row[ 'candidate_directing_exp' ] );	
	//$html .= "<h3>Πολιτική Δραστηριότητα:</h3><br>";
	//$html .= hh( $row[ 'candidate_political_activity' ] );	
	//$html .= "<br>";
	//$html .= "<h3>Σύντομη Δήλωση Ενδιαφέροντος:</h3><br>";
	//$html .= hh( $row[ 'short_interest_text' ] );	
	$html .= "<br>";

	$html .= "<h3>Συστάσεις:</h3> " . "<br>";
	$res = mysql_query( "SELECT * FROM recommendations WHERE submission_id='$id' ORDER BY from_date" );
	while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
		if (!($row_array[ 'surname' ]=='')) {
			$html .= "<b>Όνομα:</b> " . hh( $row_array[ 'surname' ] ) . " " . hh( $row_array[ 'name'] ) . "<br>";
			$html .= "<b>Υπηρεσία:</b> " . hh( $row_array[ 'organization' ] ) . "<br>";	
			$html .= "<b>Διάστημα Συνεργασίας:</b> " . hh( $row_array[ 'from_date' ] ) . " - " . hh( $row_array[ 'to_date' ] ) . "<br>";
			$html .= "<b>Αντικείμενο:</b> "	. hh( $row_array[ 'job_title' ] ) . "<br>";
			$html .= "<b>Φύση Συνεργασίας:</b> " . hh( $row_array[ 'job_description' ] ) . "<br>";
			$html .= "<b>Τηλέφωνο:</b> " . hh( $row_array[ 'phone' ] ) . "<br>";
			$html .= "<b>E-mail:</b> "	. hh( $row_array[ 'email' ] ) . "<br>";
			$html .= "<br>";
		}
	}

	$res = mysql_query( "SELECT * FROM files WHERE submission_id='$id'" );
	if (mysql_num_rows($res)) 
	{
		$html .= "<h3>Επισυναπτόμενα αρχεία:</h3><br>";
		while ( ( $row_array = mysql_fetch_assoc( $res ) ) !== false ) {
			if (!($row_array[ 'filename' ]=='')) {  
				$html .= "<b>Αρχείο:</b> " . hh( $row_array[ 'filename' ] ) . "<br>";
			}
		}	
	
	}
	$html .= "<br><br>";
	$html .= "<hr>";
	$html .= "Η αίτησή σας καταχωρήθηκε στις <b>$timestamp</b>."; 
	return $html;

}	

?>
