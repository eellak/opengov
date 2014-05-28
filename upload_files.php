<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων upload files
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
ob_start();
global $is_upload;
$is_upload = true;

require_once( 'global.php' );
initialize();
restrict();

if ( !isset( $_SESSION[ 'submission_id' ] ) ) {
	header( "Location: " . $config[ 'index' ] );
	exit();
}

$id = $_SESSION[ 'submission_id' ];

global $valid_file_types;

function get_extension( $name ) {
    $pos = strrpos( $name, "." );
    if ( $pos === false || $pos == 0 ) return "";
    return strtolower( substr( $name, $pos ) );
}

function has_valid_extension( $fname, $type ) {
	global $valid_file_types;
	$ext = get_extension( $fname );
	return in_array( $ext, $valid_file_types[ $type ] );
}

function save_of_type( $id, $type ) {
	global $config;
	if ( $_FILES[ $type ][ 'tmp_name' ] == '' ) return true;
	if ( !has_valid_extension( $_FILES[ $type ][ 'name' ], $type ) ) {
		return false;
	}
	if ( is_uploaded_file( $_FILES[ $type ][ 'tmp_name' ] ) ) {
		$fp = fopen($_FILES[ $type ][ 'tmp_name' ], 'r');
		$file_size=filesize($_FILES[ $type ][ 'tmp_name' ]);
		$file_content = fread($fp,$file_size ) or die("Error: cannot read file1");
		$content = mysql_real_escape_string($file_content) or die("Error: cannot read file2");
		fclose($fp);		
		$filename = mysql_escape_string( $_FILES[ $type ][ 'name' ] );
		mysql_query( "DELETE FROM files WHERE submission_id='$id' AND type='$type'" );
		$q = "INSERT INTO files ( submission_id, type, filename,data_size,data ) VALUES ( '$id', '$type', '$filename', '$file_size', '$content' )";
		mysql_query( $q );
		return true;
	}
	die();
	return false;
}

$error = '';
if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
	if ( !save_of_type( $id, 'img' ) ) {
		$error .= "Μη αποδεκτό αρχείο φωτογραφίας.<br>";
	}
	if ( !save_of_type( $id, 'cv' ) ) {
		$error .= "Μη αποδεκτό αρχείο βιογραφικού.<br>";
	}	
	if ( !save_of_type( $id, 'video' ) ) {
		$error .= "Μη αποδεκτό αρχείο video.<br>";
	}
	if ( $error == '' ) {
		$id = mysql_escape_string( $_SESSION[ 'submission_id' ] );
		$query = "UPDATE submissions SET confirmed='1' WHERE submission_id='$id'";	
		mysql_query( $query );
		$_SESSION[ 'confirm' ] = true;
		header( "Location: end.php" );
		exit();
	}
}

?>

<form name="form_upload" action="" method="POST"  class="form-horizontal" enctype="multipart/form-data" onSubmit="return validate();">
	<div class="page-header"><h2><small>Επισύναψη αρχείων</small></h2></div>
	<div class="alert alert-info">Η επισύναψη αρχείων είναι προαιρετική.</div>
	<?php 
		if($error != '') 
			echo '<div class="alert alert-error">'.$error.'</div>';
	?>
	<ul class="step">
		<li>
		Αποστολή φωτογραφίας:
		<input type="file" name="img" id="img">
		<label for="img" class="guidelines"><small>Φωτογραφία: Αρχείο <?php echo implode(', ', $valid_file_types['img']); ?></small></label>
		</li>
		<li>
		Αποστολή άλλου εγγράφου:
		<input type="file" name="cv" id="cv">
		<label for="cv" class="guidelines"><small> Αρχείο <?php echo implode(', ', $valid_file_types['cv']); ?></small></label>
		</li>
		<li>
		Αποστολή video:</td> 
		<input type="file" name="video" id="video">
		<label for="cv" class="guidelines"><small>Video: Αρχείο <?php echo implode(', ', $valid_file_types['video']); ?></small></label>
		</li>
	</ul>
	<input style="float:right;" type="reset" class="btn btn-small" value="Καθαρισμός" name="btn_reset">

	<div class="alert alert-info">Πατήστε "Υποβολή" για να ολοκληρώσετε τη διαδικασία.</div>

	<center>
		<input id="saveForm" class="btn btn-primary" type="submit" name="btn_submit" value="Υποβολή" />
	</center>
</form>

<img src="<?php echo SITE_URL; ?>renewSes.php" name="renewSession" width="1" height="1" id="renewSession">
<?php include('themes/footer.php'); ?>
