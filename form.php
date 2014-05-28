<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων - main form
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
ob_start();
global  $is_form;
$is_form = true; 	// For sidebar use
	
require_once( 'global.php' );
require_once( 'data.php' );
require_once( 'functions.php' );
require_once( 'db.php');

initialize();
restrict();

if ( isset( $_SESSION[ 'submission_id' ] ) ) {
	header ( "Location: ".SITE_URL."upload_files.php" );
	exit();
}

if ( !isset( $_SESSION[ 'gen' ] ) || !is_array( $_SESSION[ 'gen' ] ) || !count( $_SESSION[ 'gen' ] ) ) {
	header( "Location: ".SITE_URL );
	exit();
}

// START Get vocabularies from db 
foreach ( $data as &$row ) {
	if ( !array_key_exists( 'name', $row ) ) continue;
	$name = $row[ 'name' ];
	switch ( $row[ 'type' ] ) {
		case 'itemlist':
			$row['items']=array();
			$row['options']=array();
			if ($row['datasource_ordertype']=='asc') 
			{
				$res = mysql_query( "SELECT * from ".$row['datasource_table']." ORDER BY value ASC" );
			}
			elseif ($row['datasource_ordertype']=='none')
			{
				$res = mysql_query( "SELECT * from ".$row['datasource_table'] );
			}
			else
			{
				$res = mysql_query( "SELECT * from ".$row['datasource_table'] );
			}
			while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
				array_push($row['items'],$dbrow[ 'value' ]);
				array_push($row['options'],$dbrow[ 'option' ]);
			} 
		break;
	}
}
// END Get vocabularies from db 

$first_invalid_id = null;

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
	$postdata = $_POST;
	foreach ( array_keys( $postdata ) as $key ) {
        	$postdata[ $key ] = input( $postdata[ $key ] );
	}
	
	fill_data( $data, $postdata );
	$valid = validate( $data );
	if ( !$valid ) {
		foreach ( $data as $row ) {
			if ( count( $row[ 'invalid' ] ) ) {
				$first_invalid_id = $row[ 'invalid' ][ 0 ];
				break;
			}	
		}
	}
	else {

		$id = store_to_database( $_POST, $data, $suggest_other );
		$_SESSION[ 'submission_id' ] = $id;
		header( "Location: ".SITE_URL."upload_files.php" );
		exit();
	}
}

if ( array_key_exists( 'new_entry', $_GET ) ) {
	$_SESSION[ 'entry_id' ] = get_new_entry_id();
}

function print_captcha() {
	global $config;
	if ( !$config[ 'use_captcha' ] ) return;
	if (!( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' )) {
		if ( !isset( $_SESSION[ 'captcha_ok' ] ) ) { 
				echo '<li>';
				echo '<label class="description" style="width:60%;" for="captch">Για λόγους ασφαλείας, παρακαλώ εισάγετε τους αριθμούς της παρακάτω εικόνας στο σχετικό πεδίο.</label>	';
				echo '<img id="captcha" src="'.SITE_URL.'/lib/securimage/securimage_show.php" alt="CAPTCHA Image"/>';
				echo '<br><br>';
				echo '<input type="text" name="captcha_code" size="10" maxlength="6"/>';
				echo '</li>';
		}
	} else
	{
		if ( !isset( $_SESSION[ 'captcha_ok' ] ) ) { 
				echo '<li>';
				echo '<label class="description validate" style="width:60%;" for="captch">Για λόγους ασφαλείας, παρακαλώ εισάγετε τους αριθμούς της παρακάτω εικόνας στο σχετικό πεδίο.</label>	';
				echo '<img id="captcha" src="'.SITE_URL.'/lib/securimage/securimage_show.php" alt="CAPTCHA Image"/>';
				echo '<br><br>';
				echo '<input type="text" name="captcha_code" size="10" maxlength="6"/>';
				echo '</li>';	
		}		
	}
} 

	$msg = '';
	$title = '';
	$single_gen = ( count( $_SESSION[ 'gen' ] ) == 1 );
	if ( $single_gen ) $msg .= "<strong>Υποβάλετε αίτηση για τη θέση</strong> ";
	else $msg .= "<strong>Υποβάλετε αίτηση για τις ακόλουθες θέσεις</strong>";
?>

<?php
if ( !$single_gen ) $msg .= "<ul>";
foreach ( $_SESSION[ 'gen' ] as $gen ) {
	$gen = mysql_escape_string( $gen );
	$query = "SELECT * FROM positions WHERE office_id='$gen'";
	$res = mysql_query( $query );
	if ( $res ) {
		$row = mysql_fetch_assoc( $res );
		if ( $row !== false ) {
			$title = $row[ 'office_parent' ];
			if ( !$single_gen ) $msg .= "<li>";
			$msg .= replace_gram( $row[ 'office_name' ] );
			if ( !$single_gen )  $msg .= "</li>";
		}
	}
}
if ( !$single_gen ) $msg .= "<ul>";
?>


<form method="post" class="form-horizontal" action="" name="form" class="appnitro" >
	<div class="page-header"><h2><small><?php echo $title ;  ?></small></h2></div>
	<div class="alert alert-info"><?php echo $msg ;  ?></div>
	
	<a name="anchor_form"></a>
	<div dojoType="dijit.layout.TabContainer" id="mainTabContainer" style="overflow: auto;" doLayout="false">

		<div id="tab_first" tag="tab" dojoType="dijit.layout.ContentPane" title="Προσωπικά Στοιχεία">
			<ul class="step">
				<?php print_step( $data, 'cand_lastname' ); ?>
				<?php print_step( $data, 'cand_name' ); ?>
				<?php print_step( $data, 'cand_fathername' ); ?>
				<?php print_step( $data, 'cand_birthdate' ); ?>
				<?php print_step( $data, 'cand_marital_status' ,null,true,3); ?>
				<?php print_step( $data, 'cand_no_of_children' ,null,true,3); ?>
				<?php print_step( $data, 'cand_gender' ,null,true,3); ?>
				<?php print_step( $data, 'cand_vocation' ,null,true,4); ?>
				<?php print_step( $data, 'cand_vocation_other' ,null,true,4); ?>
				<?php print_step( $data, 'cand_occupation_type' ,null,true,3); ?>
				<?php print_step( $data, 'cand_nationality' ); ?>
				<?php print_step( $data, 'cand_address' ); ?>
				<?php print_step( $data, 'cand_email' ,null,true,3); ?>
				<?php print_step( $data, 'cand_email_verification',null,true,5 ); ?>
				<?php print_step( $data, 'cand_phone_home' ); ?>
				<?php print_step( $data, 'cand_phone_mobile' ); ?>
				<?php print_step( $data, 'cand_homepage' ); ?>
				<?php print_step( $data, 'cand_work_fulltime',null,true,4 ); ?>
				<?php if ( !$suggest_other ) print_captcha(); ?>
				<?php print_step( $data, 'cand_form_acceptance',null,true,4 ); ?>
			</ul>
		</div>

		<div id="tab4" dojoType="dijit.layout.ContentPane" title="Επαγγελματική Εμπειρία">
			<ul class="step">
			<?php print_step( $data, 'cand_workexp_privatesector',null,true,3 ); ?>
			<?php print_step( $data, 'cand_workexp_publicsector',null,true,3 ); ?>
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_prof_array' ] ) ) {
					foreach ( array_keys($data[ 'cand_prof_array' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_prof_array', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_prof_array', 0 ); 
					print_step( $data, 'cand_prof_array', 1 ); 
					print_step( $data, 'cand_prof_array', 2 ); 
					print_step( $data, 'cand_prof_array', 3 ); 
				}
			?>			
			<?php print_step( $data, 'cand_prof',null,true,4 ); ?>
			</ul>
		</div>
		<div id="tab5" dojoType="dijit.layout.ContentPane" title="Σπουδές">
			<ul class="step">
			<?php  print_step( $data, 'cand_lykeio_year' ); ?>
			<?php  print_step( $data, 'cand_edu_level',null,true,6 ); ?>			
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_edu_ba_array' ] ) ) {
					foreach ( array_keys($data[ 'cand_edu_ba_array' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_edu_ba_array', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_edu_ba_array', 0 ); 
					print_step( $data, 'cand_edu_ba_array', 1 ); 
					print_step( $data, 'cand_edu_ba_array', 2 ); 
				}
			?>				
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_edu_further_array' ] ) ) {
					foreach ( array_keys($data[ 'cand_edu_further_array' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_edu_further_array', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_edu_further_array', 0 ); 
					print_step( $data, 'cand_edu_further_array', 1 ); 
					print_step( $data, 'cand_edu_further_array', 2 ); 
				}
			?>				
			<?php print_step( $data, 'cand_edu' ); ?>
			</ul>
		</div>
		<div id="tab6" dojoType="dijit.layout.ContentPane" title="Γλώσσες">
			<ul class="step">
			<?php print_step( $data, 'cand_lang_mother1',null,true,3 ); ?>
			<?php print_step( $data, 'cand_lang_mother2',null,true,4 ); ?>
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_lang_simple_array' ] ) ) {
					foreach ( array_keys($data[ 'cand_lang_simple_array' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_lang_simple_array', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_lang_simple_array', 0 ); 
					print_step( $data, 'cand_lang_simple_array', 1 ); 
					print_step( $data, 'cand_lang_simple_array', 2 ); 
					print_step( $data, 'cand_lang_simple_array', 3 ); 
					print_step( $data, 'cand_lang_simple_array', 4 ); 
					print_step( $data, 'cand_lang_simple_array', 5 ); 
				}
			?>		
			</ul>
		</div>
		<div id="tab7" dojoType="dijit.layout.ContentPane" title="Γνώσεις ΤΠΕ">
			<ul class="step">
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_computers_array' ] ) ) {
					foreach ( array_keys($data[ 'cand_computers_array' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_computers_array', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_computers_array', 0 ); 
				}
			?>	
			<?php print_step( $data, 'cand_computers' ); ?>			
			</ul>
		</div>

		<div id="tab8" dojoType="dijit.layout.ContentPane" title="Δραστηριότητα">
			<ul class="step">
			<?php print_step( $data, 'cand_social_activity' ); ?>
			<?php print_step( $data, 'cand_directing_exp' ); ?>
			</ul>	
		</div>

		<div id="tab_last" dojoType="dijit.layout.ContentPane" title="Συστάσεις">
			<ul id="cand_reco_container" class="step">
			<?php  
				if ( array_key_exists( 'data', $data[ 'cand_reco' ] ) ) {
					foreach ( array_keys($data[ 'cand_reco' ][ 'data' ]) as $id ) {
						print_step( $data, 'cand_reco', $id ); 
					}
				}
				else {
					print_step( $data, 'cand_reco', 0 ); 
					print_step( $data, 'cand_reco', 1 ); 
					print_step( $data, 'cand_reco', 2 ); 
					print_step( $data, 'cand_reco', 3 ); 
				}	
			?>
			</ul>
		</div>

	</div>

	<div id="navigatorer" style="text-align:center; margin:10px 0px 20px 0px;">
		<input type="button" class="btn btn btn-info"  id="btn_previous" value="Προηγούμενη Καρτέλα">
		<input type="button" class="btn btn btn-info" id="btn_next" value="Επόμενη Καρτέλα">
	</div>

	<div class="alert alert-block">
		<p><?php print_step( $data, 'final_confirmation', null, false ); ?></p><br />
		<input type="submit" id="btn_save" class="btn btn-primary" name="btn_save" value="Υποβολή και Συνέχεια">	
	</div>

</form>
<?php include('themes/footer.php'); ?>
