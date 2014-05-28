<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων db functions
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
require_once( 'global.php' );

initialize();

function store_to_database( $postdata, $data, $other ) {

	foreach ( $postdata as $k => &$v ) {
		$v =& input( $v );
	}

	$protocol = '';
	$position_id = 1;
	$db_rel = array( 
		'submission_protocol' => $protocol,
		'position_id' => $position_id,
		'candidate_prof' => mysql_escape_string( $postdata[ 'cand_prof' ] ),
		'candidate_edu' => mysql_escape_string( $postdata[ 'cand_edu' ] ),
		'candidate_activity' => mysql_escape_string( $postdata[ 'cand_activity' ] ),
		'candidate_name' => mysql_escape_string( $postdata[ 'cand_name' ] ),
		'candidate_surname' => mysql_escape_string( $postdata[ 'cand_lastname' ] ),
		'candidate_fathername' => mysql_escape_string( $postdata[ 'cand_fathername' ]),
		'candidate_nationality' => mysql_escape_string( $postdata[ 'cand_nationality' ]),
		'candidate_address_road' => mysql_escape_string( $postdata[ 'cand_address_road' ] ),
		'candidate_address_number' => mysql_escape_string( $postdata[ 'cand_address_number' ] ),
		'candidate_address_city' => mysql_escape_string( $postdata[ 'cand_address_city' ] ),
		'candidate_address_areacode' => mysql_escape_string( $postdata[ 'cand_address_areacode' ] ),
		'candidate_address_country' => mysql_escape_string( $postdata[ 'cand_address_country' ] ),
		'candidate_address_lgoid' => mysql_escape_string( $postdata[ 'cand_address_municipality' ] ),
		'candidate_email' => mysql_escape_string( $postdata[ 'cand_email' ] ),
		'candidate_tel' => mysql_escape_string( $postdata[ 'cand_phone_home' ] ),
		'candidate_mobile' => mysql_escape_string( $postdata[ 'cand_phone_mobile' ] ),
		'candidate_homepage' => mysql_escape_string( $postdata[ 'cand_homepage' ] ),
		'candidate_theseis_endiaferontos' => mysql_escape_string( $postdata[ 'cand_theseis_endiaferontos' ] ),
		'is_recommended' => $other ? '1' : '0',
		'recommender_name' => $other ? mysql_escape_string( $postdata[ 'sug_name' ] ) : '',
		'recommender_surname' => $other ? mysql_escape_string( $postdata[ 'sug_lastname' ] ) : '',
		'recommender_job' => $other ? mysql_escape_string( $postdata[ 'sug_capacity' ] ) : '',
		'recommender_connection' => $other ? mysql_escape_string( $postdata[ 'sug_relation' ] ) : '',
		'recommender_email' => $other ? mysql_escape_string( $postdata[ 'sug_email' ] ) : '',
		'recommender_tel' => $other ? mysql_escape_string( $postdata[ 'sug_phone' ] ) : '',
		'recommender_is_aware' => ( $other && array_key_exists( 'sug_is_aware', $postdata ) ) ? mysql_escape_string( $postdata[ 'sug_is_aware' ] ) : '0',
		'candidate_recommendation' => '',
		'office_priorities' => '',
		'candidate_more_info' => '',
		'more_useful_info' => '',
		'gen1_id' => count( $_SESSION[ 'gen' ] ) > 0 ? $_SESSION[ 'gen' ][ 0 ] : -1,
		'gen2_id' => count( $_SESSION[ 'gen' ] ) > 1 ? $_SESSION[ 'gen' ][ 1 ] : -1,
		'gen3_id' => count( $_SESSION[ 'gen' ] ) > 2 ? $_SESSION[ 'gen' ][ 2 ] : -1,
		'gen4_id' => count( $_SESSION[ 'gen' ] ) > 3 ? $_SESSION[ 'gen' ][ 3 ] : -1,
		'gen5_id' => count( $_SESSION[ 'gen' ] ) > 4 ? $_SESSION[ 'gen' ][ 4 ] : -1,
		'candidate_birth_day' => mysql_escape_string( $postdata[ 'cand_birthdate_day' ] ),
		'candidate_birth_month' => mysql_escape_string( $postdata[ 'cand_birthdate_month' ] ),
		'candidate_birth_year' => mysql_escape_string( $postdata[ 'cand_birthdate_year' ] ),
		'languages_exp' => mysql_escape_string( $postdata[ 'cand_languages' ] ),
		'computer_exp' => mysql_escape_string( $postdata[ 'cand_computers' ] ),
		'short_interest_text' => mysql_escape_string( $postdata[ 'cand_simple_statement' ] ),
		'current_position' => mysql_escape_string( $postdata[ 'cand_current_position' ] ),
		'candidate_has_degree' => mysql_escape_string( $postdata[ 'cand_has_degree' ] ),
		'candidate_has_metaptixiako' => mysql_escape_string( $postdata[ 'cand_has_metaptixiako' ] ),
		'candidate_workexp_privatesector' => mysql_escape_string( $postdata[ 'cand_workexp_privatesector' ] ),
		'candidate_workexp_publicsector' => mysql_escape_string( $postdata[ 'cand_workexp_publicsector' ] ),
		'candidate_work_fulltime' => mysql_escape_string( $postdata[ 'cand_work_fulltime' ] ),
		'candidate_vocation' => mysql_escape_string( $postdata[ 'cand_vocation' ] ),
		'candidate_theseis_endiaferontos' => mysql_escape_string( $postdata[ 'cand_theseis_endiaferontos' ] ),
		'candidate_theseis_endiaferontos_logos' => mysql_escape_string( $postdata[ 'cand_theseis_endiaferontos_logos' ] ),
		'candidate_gender' => mysql_escape_string( $postdata[ 'cand_gender' ] ),
		'candidate_occupation_type' => mysql_escape_string( $postdata[ 'cand_occupation_type' ] ),
		'candidate_lang_mother1' => mysql_escape_string( $postdata[ 'cand_lang_mother1' ] ),
		'candidate_lang_mother2' => mysql_escape_string( $postdata[ 'cand_lang_mother2' ] ),
		'candidate_gg_protocol_id' => mysql_escape_string( $postdata[ 'cand_gg_protocol_id' ] ),
		'candidate_marital_status' => mysql_escape_string( $postdata[ 'cand_marital_status' ] ),
		'candidate_no_of_children' => mysql_escape_string( $postdata[ 'cand_no_of_children' ] ),
		'candidate_social_activity' => mysql_escape_string( $postdata[ 'cand_social_activity' ] ),
		'candidate_political_activity' => mysql_escape_string( $postdata[ 'cand_political_activity' ] ),
		'candidate_directing_exp' => mysql_escape_string( $postdata[ 'cand_directing_exp' ] ),
		'candidate_lykeio_year' => mysql_escape_string( $postdata[ 'cand_lykeio_year' ] ),
		'candidate_edu_level' => mysql_escape_string( $postdata[ 'cand_edu_level' ] ),
		'years_of_experience' => mysql_escape_string( $postdata[ 'cand_years_of_experience' ] )
		);
		
	$cols = '';
	$values = '';
	foreach ( $db_rel as $col => $value ) {
		if ( $cols !== '' ) $cols .= ', ';
		if ( $values !== '' ) $values .= ', ';
		$cols .= $col;
		$values .= "'$value'";	
	}
	$cols .= ", submission_date";
	$values .= ", NOW()";

	$query = "INSERT INTO submissions ( " . $cols . " ) VALUES ( " . $values . " )";
	
	if ( mysql_query( $query ) === false ) error( "Πρόβλημα στη σύνδεση με τη βάση δεδομένων" );
	$id = mysql_insert_id( );
	if ( $id === 0 || $id === false ) {
		$query = mysql_escape_string( $query );
		$q = "INSERT INTO errors ( q ) values ( '$query' )";
		mysql_query( $q );
		error( "Παρουσιάστηκε ένα πρόβλημα στην καταχώρηση των δεδομένων. Παρακαλούμε προσπαθήστε αργότερα." );
		return 0;
	}

	if ($postdata[ 'cand_vocation_other' ]) {
		$query = sprintf( "UPDATE submissions SET candidate_vocation='".$postdata[ 'cand_vocation_other' ]."' WHERE submission_id='$id'", 13524 + $id ); 
		mysql_query( $query );
	}
	$query = sprintf( "UPDATE submissions SET submission_protocol='%d' WHERE submission_id='$id'", 14524 + $id ); 
	mysql_query( $query );

	foreach ( $data[ 'cand_reco' ][ 'data' ] as $count => $row ) {
		$query = sprintf( "INSERT INTO recommendations (
					submission_id,	
					from_date, to_date,
					organization, job_title, job_description,
					name, surname, phone, email )
					VALUES
					( $id,
					'%s', '%s',
					'%s', '%s', '%s',
					'%s', '%s', '%s', '%s' ) ",
					mysql_escape_string( $row[ 'from' ] ), 
					mysql_escape_string( $row[ 'to' ] ),
					mysql_escape_string( $row[ 'service' ] ), 
					mysql_escape_string( $row[ 'subject' ] ), 
					mysql_escape_string( $row[ 'coop_type' ] ),
					mysql_escape_string( $row[ 'name' ] ),
					mysql_escape_string( $row[ 'lastname' ] ),
					mysql_escape_string( $row[ 'phone' ] ),
					mysql_escape_string( $row[ 'email' ] ) );
		mysql_query( $query );
	}

	foreach ( $data[ 'cand_prof_array' ][ 'data' ] as $count => $row ) {
		$query = sprintf( "INSERT INTO work (
					submission_id,	
					period_from,
					period_to,
					position,
					activities,
					employer_name,
					employer_city,
					employer_postcode,
					employer_type  )
					VALUES
					( $id,
					'%s', '%s',
					'%s', '%s', '%s',
					'%s', '%s', '%s' ) ",
					mysql_escape_string( $row[ 'period_from' ] ), 
					mysql_escape_string( $row[ 'period_to' ] ),
					mysql_escape_string( $row[ 'position' ] ), 
					mysql_escape_string( $row[ 'activities' ] ), 
					mysql_escape_string( $row[ 'employer_name' ] ),
					mysql_escape_string( $row[ 'employer_city' ] ),
					mysql_escape_string( $row[ 'employer_postcode' ] ),
					mysql_escape_string( $row[ 'employer_type' ] )
					);
		mysql_query( $query );
	}

	foreach ( $data[ 'cand_edu_ba_array' ][ 'data' ] as $count => $row ) {
	
		$query = sprintf( "INSERT INTO education_ba (
					submission_id,	
					country, 
					name,
					name_other, 
					department, 
					year_from, 
					year_to, 
					final_grade, 
					comments)
					VALUES
					( $id,
					'%s', '%s',
					'%s', '%s', '%s',
					'%s','%s','%s' ) ",
					mysql_escape_string( $row[ 'country' ] ), 
					mysql_escape_string( $row[ 'name' ] ),
					mysql_escape_string( $row[ 'name_other' ] ), 
					mysql_escape_string( $row[ 'department' ] ), 
					mysql_escape_string( $row[ 'year_from' ] ),
					mysql_escape_string( $row[ 'year_to' ] ),
					mysql_escape_string( $row[ 'final_grade' ] ),
					mysql_escape_string( $row[ 'comments' ])
					);
					
		mysql_query( $query );
	}
	
	foreach ( $data[ 'cand_edu_further_array' ][ 'data' ] as $count => $row ) {
		$query = sprintf( "INSERT INTO education_further (
					submission_id,	
					country, 
					name,
					name_other, 
					department, 
					year_from, 
					year_to, 
					final_grade, 
					comments, 
					title )
					VALUES
					( $id,
					'%s', '%s',
					'%s', '%s', '%s',
					'%s','%s','%s','%s' ) ",
					mysql_escape_string( $row[ 'country' ] ), 
					mysql_escape_string( $row[ 'name' ] ),
					mysql_escape_string( $row[ 'name_other' ] ), 
					mysql_escape_string( $row[ 'department' ] ), 
					mysql_escape_string( $row[ 'year_from' ] ),
					mysql_escape_string( $row[ 'year_to' ] ),
					mysql_escape_string( $row[ 'final_grade' ] ),
					mysql_escape_string( $row[ 'comments' ] ),
					mysql_escape_string( $row[ 'title' ] )
					);
		mysql_query( $query );
	}

	foreach ( $data[ 'cand_lang_simple_array' ][ 'data' ] as $count => $row ) {
		$query = sprintf( "INSERT INTO languages_simple (
					submission_id,	
					language, 
					level )
					VALUES
					( $id,
					'%s', '%s' ) ",
					mysql_escape_string( $row[ 'language' ] ), 
					mysql_escape_string( $row[ 'level' ] )
					);
		mysql_query( $query );
	}
	foreach ( $data[ 'cand_computers_array' ][ 'data' ] as $count => $row ) {
		$query = sprintf( "INSERT INTO computers (
					submission_id,	
					word_processing,
					db_knowledge,
					spreadsheets,
					networks,
					internet)
					VALUES
					( $id,
					'%s', '%s',
					'%s', '%s', 
					'%s' ) ",
					mysql_escape_string( $row[ 'word_processing' ] ), 
					mysql_escape_string( $row[ 'databases' ] ),
					mysql_escape_string( $row[ 'spreadsheets' ] ), 
					mysql_escape_string( $row[ 'networks' ] ), 
					mysql_escape_string( $row[ 'internet' ] )
					);
		mysql_query( $query );
	}
	
	return $id;
}

?>