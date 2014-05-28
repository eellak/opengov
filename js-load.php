<?php 
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων Javascript
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
	global $is_form;
	echo '<style type="text/css">@import url("'.SITE_URL.'themes/js/release/dojo/dijit/themes/tundra/ggk.css")</style>';
	echo '<script type="text/javascript" src="'.SITE_URL.'themes/js/release/dojo/dojo/dojo.js" djConfig="parseOnLoad: true"></script>';
	echo '<script type="text/javascript" src="'.SITE_URL.'themes/js/release/dojo/dojo/ggk.js"></script>';
	if($is_form)
		echo '<script type="text/javascript" src="'.SITE_URL.'init.js"></script>';
	echo '<link href="'.SITE_URL.'themes/css/custom.css" rel="stylesheet">';
?>