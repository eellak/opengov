<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων global functions and Settings
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
session_start();

require_once('themes/header.php' );

$config = array( );
$config[ 'db' ][ 'hostname' ] 	= DB_HOST;
$config[ 'db' ][ 'user' ] 		= DB_USER;
$config[ 'db' ][ 'password' ] 	= DB_PASSWORD;
$config[ 'db' ][ 'database' ] 	= DB_NAME;

require_once('lib/common.php' );
?>
