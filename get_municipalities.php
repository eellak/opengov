<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων - function for municipalities
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
require_once( 'global.php' );
require_once( 'data.php' );
require_once( 'functions.php' );
require_once( 'db.php');
     mysql_query( "SET NAMES utf8" );
	 setlocale( LC_ALL, "el_GR.UTF-8" );
	 $rquery="SELECT * from kapodistrias WHERE regionid='".$_REQUEST[ 'regionid' ]."'";// 
	$res = mysql_query( $rquery );
	//echo $rquery;
	$i=0;
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if ($i==0) 
		{
			echo "<option value=\"".$dbrow[ 'lgoid' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'lgoid' ]."\">";
		}
		echo $dbrow[ 'lgotype' ]." ".$dbrow[ 'lgo' ]."</option>";
		$i++;
	} 
	
?>