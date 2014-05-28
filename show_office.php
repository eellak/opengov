<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων check for duplication with session data
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
ob_start();
require_once( 'global.php' );
initialize();

if ( $_SERVER[ 'REQUEST_METHOD' ] == 'POST' ) {
	@session_unset();
	@session_destroy();
	session_start();
	header( "Location: ".SITE_URL."" );
	exit();
}
?>
<form id="form" class="form-horizontal" method="post" action="" onSubmit="return confirm( 'Είστε σίγουρος(η); Εάν επεξεργάζεστε στοιχεία σε άλλο παράθυρο αυτά θα χαθούν.' );">
	<div class="page-header"><h2><small>Προσοχή!</small></h2></div>
	<div class="alert alert-error">
		Ενδεχομένως να συμπληρώνετε την αίτηση σε άλλο παράθυρο. Αν θέλετε να τη διακόψετε για να υποβάλλετε νέα στοιχεία, πατήστε <input type="submit" value="Εδώ">.<br />
		<strong>Προσοχή:</strong> Στην περίπτωση αυτή τα στοιχεία που επεξεργάζεστε θα χαθούν!
	</div>
</form>	
<?php
include('themes/footer.php');
?>