<?php
/**
* @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων system Settings
* @author Fotis Routsis <fotis.routsis@gmail.com>
* @package drash4
* @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
*/
/**
 * System Settings
 */
 
define( 'SITE_URL', 'TO URL TOY SITE ' );						/** The base URL of the site */

/** The name to appear on header - Used also as browser title */
define( 'SITE_NAME', 'Προσκλήσεις Μετακλητών Υπαλλήλων' );					

/** The description to appear on header - Used also as meta description */
define( 'SITE_DESCR', 'Προσκλήσεις Μετακλητών Υπαλλήλων' );					

/** The footer */
define( 'SITE_FOOTER', 'Προσκλήσεις Μετακλητών Υπαλλήλων' );			

define( 'USE_SSL', false );													/** Force SSL Usage */		

global $valid_file_types;

$valid_file_types = array(
	'img' => array( '.gif', '.jpg', '.jpeg', '.png' ),
	'cv' => array( '.txt', '.doc', '.docx', '.pdf', '.odt' ),
	'video' => array( '.avi', '.ogv', '.mpeg', '.mpg', '.flv' )
);

?>