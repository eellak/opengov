<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων - select the main actions
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
	ob_start();
	global  $is_submit;
	$is_submit = true; 	// For sidebar use
	
	require_once( 'global.php' );
	initialize();
	if ( isset( $_SESSION[ $config[ 'session_name' ] ] ) ) {
		header( "Location: ".SITE_URL."show_office.php" );
		exit();
	}

	$err = '';
	
	if (!(mysql_real_escape_string($_REQUEST['option'])==$_REQUEST['option'])) { $_REQUEST['option']=''; }

	if ($_REQUEST['option']){
		$query=  "SELECT * FROM `positions` WHERE `active` =1 AND `option` = '".$_REQUEST['option']."'  ORDER BY sortedview";
		$res = mysql_query(  $query);
		$query=  "SELECT * FROM  positions_subcategory WHERE `option`='".$_REQUEST['option']."'";
		$res_subcategory = mysql_query(  $query);
		$row = mysql_fetch_assoc( $res_subcategory );
		$max_selections=$row['max_selection'];
	} else {
		header( "Location: ".SITE_URL."" );
		exit();
	}
	
	if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
		if ( array_key_exists( 'office', $_POST ) && is_array( $_POST[ 'office' ] ) ) {
			$positions = input( $_POST[ 'office' ] );
			if ( count( $positions ) > 0 && count( $positions ) <= $max_selections ) {
				if ( !is_array( $positions ) ) {
					$_SESSION[ 'gen' ] = array( $positions );
				} else {
					$_SESSION[ 'gen' ] = $positions;
				}
				$_SESSION[ $config[ 'session_name' ] ] = 1;
				header( "Location: ".SITE_URL."form.php" );
				exit();
			}
			else if ( count( $positions ) > 1 ) {	
				if ($max_selections==1) $err = "Μπορείτε να επιλέξετε μόνο μέχρι 1 από τα παρακάτω";
				if ($max_selections>1) $err = "Μπορείτε να επιλέξετε μόνο μέχρι ".$max_selections." από τα παρακάτω";
			}
		}		
	}
	
	$title = '';
	$positionz = '';
	if (mysql_num_rows($res)>0) {
		$positionz = '<div class="control-group"><div class="controls">';
		while ( ($row = mysql_fetch_assoc( $res ) ) !== false ) {
			$title = $row[ 'office_parent' ];	
			if (mysql_num_rows( $res )==1){
				$positionz .= '<label class="checkbox"><input type="checkbox" name="office[]" value='.$row[ 'office_id' ]. ' checked >';	
			} else {
				$positionz .= '<label class="checkbox"><input type="checkbox" name="office[]" value='.$row[ 'office_id' ]. '>';	
			}
			$positionz .= $row[ 'office_name' ].'</label>';	
			
		}
		$positionz .= '</div></div>';
	} else {	
		$positionz = '<ul class="nav nav-tabs nav-stacked">';
		$err = 'Κατάσταση πρόσκλησης : <b>κλειστή</b>';
		$res0 = mysql_query(  "SELECT * FROM positions WHERE active='0' AND `option` = '".$_REQUEST['option']."'  ORDER BY sortedview");
		while ( ($row = mysql_fetch_assoc( $res0 ) ) !== false ) {
			$title = $row[ 'office_parent' ];
			$positionz .= '<li class="disabled"><a href="#">'.$row[ 'office_name' ].'</a></li>';	
		} 
		$positionz .= '</ul>';
	}
	
?>
<form id="form" class="form-horizontal" method="post" action="">
	<div class="page-header"><h2><small><?php echo $title ;  ?></small></h2></div>
<?php
	if (mysql_num_rows($res)>0) {
		if (($max_selections==1)&& (mysql_num_rows($res)==1)){
			echo '<div class="alert alert-info">Eπιλέξτε το παρακάτω και συνεχίστε στο επόμενο βήμα.</div>';
		} else {	
			echo '<div class="alert alert-info">Eπιλέξτε έως '.$max_selections.' από τα παρακάτω και συνεχίστε στο επόμενο βήμα.</div>';
		}
		if ($err) { echo '<div class="alert alert-error">'.$err.'</div>';  }
	} else { echo '<div class="alert alert-error">'.$err.'</div>'; }

	echo $positionz ;
	echo '<div class="alert">Διαβάστε το περιεχόμενο της Πρόσκλησης <a href="'.SITE_URL.'/view.php?option='.$_REQUEST['option'].'">εδώ</strong></a></div>';
	
	if (mysql_num_rows($res)>0){ echo '<input id="saveForm" class="btn btn-primary" type="submit" name="submit" value="Συνέχεια" />'; } 
?>			
</form>	
<?php include('themes/footer.php'); ?>