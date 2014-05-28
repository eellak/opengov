<?php
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων - required functions
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
function print_input_date( $row ) {
		$id = $row[ 'id' ];
		$name = $row[ 'name' ];
		$data = array( 'day' => '', 'month' => '', 'year' => '' );
		if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];

		echo "<div id=\"$id\">\n";

		$sid = $id . '_day';
		$sname = $name . '_day';
		$value = htmlspecialchars( $data[ 'day' ] );
		$lid = "label_$sid";
		$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
		echo "<span>\n";
		echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"2\" maxlength=\"2\" value=\"$value\"> / ";
		echo "<label $error id=\"$lid\" for=\"$sid\">ΗΗ</label>";
		echo "</span>\n";

		$sid = $id . '_month';
		$sname = $name . '_month';
		$value = htmlspecialchars( $data[ 'month' ] );
		$lid = "label_$sid";
		$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
		echo "<span>\n";
		echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"2\" maxlength=\"2\" value=\"$value\"> / ";
		echo "<label $error id=\"$lid\" for=\"$sid\">ΜΜ</label>";
		echo "</span>\n";

		$sid = $id . '_year';
		$sname = $name . '_year';
		$value = htmlspecialchars( $data[ 'year' ] );
		$lid = "label_$sid";
		$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
		echo "<span>\n";
		echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"4\" maxlength=\"4\" value=\"$value\">";
		echo "<label $error id=\"$lid\" for=\"$sid\">ΕΕΕΕ</label>";
		echo "</span>\n";

		echo "</div>\n";
}

function print_input_address( $row ) {
	$id = $row[ 'id' ];
	$name = $row[ 'name' ];
	$data = array( 'road' => '', 'number' => '','city' => '',  'country' => '', 'areacode' => '', 'region' => '', 'municipality' => '' );
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];

	$sid = $id . '_road';
	$sname = $name . '_road';
	$value = htmlspecialchars( $data[ 'road' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>\n";
	echo "<input id=\"$sid\" name=\"$sname\" class=\"element text medium\" type=\"text\" value=\"$value\">";
	echo "<label $error id=\"$sid\" for=\"$sid\">Οδός</label>";
	print "</div>\n";

	$sid = $id . '_number';
	$sname = $name . '_number';
	$value = htmlspecialchars( $data[ 'number' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	print "<div>\n";
	echo "<input id=\"$sid\" name=\"$sname\" class=\"element text small\" type=\"text\" value=\"$value\">";
	echo "<label $error id=\"$sid\" for=\"$sid\">Αριθμός</label>";
	print "</div>\n";


	$sid = $id . '_city';
	$sname = $name . '_city';
	$value = $data[ 'city' ];
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
        print "<div>\n";
        echo "<input id=\"$sid\" name=\"$sname\" class=\"element text small\" type=\"text\" value=\"$value\">";
        echo "<label $error id=\"$sid\" for=\"$sid\">Πόλη</label>";
        print "</div>\n";
	
	$sid = $id . '_country';
	$sname = $name . '_country';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'country' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\">";
	
	$res = mysql_query( "SELECT * from voc_countries ORDER BY value ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'country' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'country' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Χώρα</label>";
	echo "</div>\n";	
	
	$sid = $id . '_region';
	$sname = $name . '_region';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'region' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\" onChange=\"change_region_subform();\">";
	echo "<option value=\"-1\">Εκτός Ελλάδος</option>";
	$res = mysql_query( "SELECT * from kapodistrias GROUP BY region ORDER BY region ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'regionid' ]=="9") && !($data[ 'region' ])) {
			echo "<option value=\"".$dbrow[ 'regionid' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'region' ]==$dbrow[ 'regionid' ]) 
		{
			echo "<option value=\"".$dbrow[ 'regionid' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'regionid' ]."\">";
		}
		echo $dbrow[ 'region' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Περιφέρεια</label>";
	echo "</div>\n";	
	
	$sid = $id . '_municipality';
	$sname = $name . '_municipality';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'municipality' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\">";
	if ($data[ 'region' ]=='') {$data[ 'region' ]="9";}
	$res = mysql_query( "SELECT * from kapodistrias WHERE regionid='".$data[ 'region' ]."' ORDER BY region ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'lgo' ]=="ΑΓΙΑΣ ΒΑΡΒΑΡΑΣ") && !($data[ 'municipality' ])) {
			echo "<option value=\"".$dbrow[ 'lgoid' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'municipality' ]==$dbrow[ 'lgoid' ]) 
		{
			echo "<option value=\"".$dbrow[ 'lgoid' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'lgoid' ]."\">";
		}
		echo $dbrow[ 'lgotype' ]." ".$dbrow[ 'lgo' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Δήμος ή Κοινότητα</label>";
	echo "</div>\n";	
	
	

	$sid = $id . '_areacode';
	$sname = $name . '_areacode';
	$value = htmlspecialchars( $data[ 'areacode' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	print "<div>\n";
	echo "<input id=\"$sid\" name=\"$sname\" class=\"element text\" type=\"text\" size=\"5\" maxlength=\"5\" value=\"$value\">";
	echo "<label $error id=\"$sid\" for=\"$sid\">Τ.Κ.</label>";
	print "</div>\n";
}

function print_input_education( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];

	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";
	$sid = $id . '_from';
	$sname = $name . '_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<small $error id=\"$lid\">Από:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";

	$sid = $id . '_to';
	$sname = $name . '_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<small> - </small><small $error id=\"$lid\">Έως:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "</div>\n";

	$sid = $id . '_subject';
	$sname = $name . '_subject';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'subject' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Αντικείμενο σπουδών</label>";
	echo "</div>\n";

	$sid = $id . '_title';
	$sname = $name . '_title';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'title' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τίτλος που αποκτήθηκε</label>";
	echo "</div>\n";

	$sid = $id . '_degree';
	$sname = $name . '_degree';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'degree' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τίτλος πτυχίου</label>";
	echo "</div>\n";

	$sid = $id . '_country';
	$sname = $name . '_country';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'country' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text medium\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Χώρα σπουδών</label>";
	echo "</div>\n";

	$sid = $id . '_institute';
	$sname = $name . '_institute';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'institute' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Οργανισμός που παρείχε την εκπαίδευση</label>";
	echo "</div>\n";

	$sid = $id . '_grade';
	$sname = $name . '_grade';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'grade' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text small\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Βαθμός αποφοίτησης</label>";
	echo "</div>\n";

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;

}

function print_input_profession( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];

	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";
	$sid = $id . '_from';
	$sname = $name . '_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<small $error id=\"$lid\">Από:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";

	$sid = $id . '_to';
	$sname = $name . '_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<small> - </small><small $error id=\"$lid\">Έως:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "</div>\n";

	$sid = $id . '_foreas';
	$sname = $name . '_foreas';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'foreas' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Φορέας</label>";
	echo "</div>\n";

	$sid = $id . '_job';
	$sname = $name . '_job';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'job' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Απασχόληση στη θέση που κατείχε</label>";
	echo "</div>\n";

	$sid = $id . '_activities';
	$sname = $name . '_activities';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'activities' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea medium\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Κύριες δραστηριότητες και αρμοδιότητες</label>";
	echo "</div>\n";

	$sid = $id . '_supervisor';
	$sname = $name . '_supervisor';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'supervisor' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea small\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Άμεσος προϊστάμενος (και στοιχεία επικοινωνίας)</label>";
	echo "</div>\n";

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;
	if ( $count > $min_count ) {
		$func = "on_btn_del_click( 'li_cand_prof_$count' );";
		echo "<input type=\"button\" id=\"btn_del_prof_$count\" onclick=\"$func\" value=\"Διαγραφή Θέσης\">";
	}
}


function print_input_prof_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";
	$sid = $id . '_period_from';
	$sname = $name . '_period_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'period_from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<small $error id=\"$lid\">Από:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";

	$sid = $id . '_period_to';
	$sname = $name . '_period_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'period_to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<small> - </small><small $error id=\"$lid\">Έως:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "</div>\n";

	$sid = $id . '_position';
	$sname = $name . '_position';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'position' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Απασχόληση ή θέση που κατείχατε</label>";
	echo "</div>\n";

	
	$sid = $id . '_activities';
	$sname = $name . '_activities';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'activities' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea medium\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Κύριες δραστηριότητες και αρμοδιότητες</label>";
	echo "</div>\n";

	
	$sid = $id . '_employer_name';
	$sname = $name . '_employer_name';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'employer_name' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Επωνυμία εργοδότη</label>";
	echo "</div>\n";

	$sid = $id . '_employer_city';
	$sname = $name . '_employer_city';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'employer_city' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Πόλη εργοδότη</label>";
	echo "</div>\n";

	$sid = $id . '_employer_postcode';
	$sname = $name . '_employer_postcode';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'employer_postcode' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text\" size=\"5\" maxlength=\"5\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">T.K. εργοδότη</label>";
	echo "</div>\n";

	$sid = $id . '_employer_type';
	$sname = $name . '_employer_type';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'employer_type' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\">";
	$res = mysql_query( "SELECT * from voc_vocations ORDER BY value ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'employer_type' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'employer_type' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Είδος της επιχείρησης ή του κλάδου</label>";
	echo "</div>\n";
	

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;

}

function print_input_lang_simple_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";
	
	$sid = $id . '_language';
	$sname = $name . '_language';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'language' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 300px\">";
	$res = mysql_query( "SELECT * from voc_languages ORDER BY value ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'language' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'language' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Γλώσσα</label>";
	echo "</div>\n";
	

	$sid = $id . '_level';
	$sname = $name . '_level';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'level' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * from voc_lang_skill_levels_simple ORDER BY value ASC" );

	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'level' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'level' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Ικανότητα χρήσης</label>";
	echo "</div>\n";


	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;
}


function print_input_computers_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";

	$sid = $id . '_word_processing';
	$sname = $name . '_word_processing';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'word_processing' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * from voc_computer_exp" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'word_processing' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'word_processing' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Επεξεργασία κειμένου</label>";
	echo "</div>\n";
	
	
	$sid = $id . '_databases';
	$sname = $name . '_databases';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'databases' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * from voc_computer_exp" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'databases' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'databases' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Βάσεις δεδομένων </label>";
	echo "</div>\n";

	$sid = $id . '_spreadsheets';
	$sname = $name . '_spreadsheets';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'spreadsheets' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * from voc_computer_exp" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'spreadsheets' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'spreadsheets' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Υπολογιστικά φύλλα</label>";
	echo "</div>\n";
	

	$sid = $id . '_networks';
	$sname = $name . '_networks';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'networks' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * FROM voc_computer_exp" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'networks' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'networks' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Δίκτυα</label>";
	echo "</div>\n";
	
	
	$sid = $id . '_internet';
	$sname = $name . '_internet';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'internet' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 180px\">";
	$res = mysql_query( "SELECT * from voc_computer_exp" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'internet' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'internet' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Εργαλεία internet (χρήση e-mail, φυλλομετρητών (browsers))</label>";
	echo "</div>\n";


	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;

}

function print_input_edu_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";
	$sid = $id . '_period_from';
	$sname = $name . '_period_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'period_from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<small $error id=\"$lid\">Από:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";

	$sid = $id . '_period_to';
	$sname = $name . '_period_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'period_to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<small> - </small><small $error id=\"$lid\">Έως:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "</div>\n";
	
	
	$sid = $id . '_title';
	$sname = $name . '_title';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'title' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τίτλος του πιστοποιητικού ή διπλώματος</label>";
	echo "</div>\n";

	
	$sid = $id . '_skills';
	$sname = $name . '_skills';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'skills' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea small\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Κύρια θέματα / επαγγελματικές δεξιότητες</label>";
	echo "</div>\n";

	
	$sid = $id . '_organisation';
	$sname = $name . '_organisation';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'organisation' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Αναφέρετε την επωνυμία και το είδος του οργανισμού στον οποίο φοιτήσατε</label>";
	echo "</div>\n";

	$sid = $id . '_level';
	$sname = $name . '_level';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'level' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 320px\">";
	$res = mysql_query( "SELECT * from voc_degree_types" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'level' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'level' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Επίπεδο σπουδών</label>";
	echo "</div>\n";
	

	$sid = $id . '_final_grade';
	$sname = $name . '_final_grade';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'final_grade' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Βαθμός αποφοίτησης</label>";
	echo "</div>\n";

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;

}

function print_input_edu_ba_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";

	$sid = $id . '_country';
	$sname = $name . '_country';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'country' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "
	
	";
	
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 320px\" onChange=\"change_edu_subform('".$id."');\">";
	$res = mysql_query( "SELECT * from voc_countries" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'country' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'country' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
			if ($dbrow[ 'value' ]=="Ελλάδα") {$name_other_disable=false;} else {$name_other_disable=true;}
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
	if (!($data[ 'country' ])) $name_other_disable=false;
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Χώρα σπουδών</label>";
	echo "</div>\n";
	
	$sid = $id . '_name';
	$sname = $name . '_name';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'name' ] );
	$lid = "label_$sid";
	$did = "div_$sid"; 
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div id=\"".$did."\">";
	
	if (!($name_other_disable)) {
		echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\">";
	}
	else
	{
		echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\" disabled=\"true\">";
	}
	$res = mysql_query( "SELECT * from voc_universities_gr ORDER BY value ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'name' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'name' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Εκπαιδευτικό ίδρυμα ΠΑΝΕΠΙΣΤΗΜΙΟ - ΤΕΙ (Επιλέξτε \"Ελλάδα\" για να εμφανιστούν )</label>";
	echo "</div>\n";	

	$sid = $id . '_name_other';
	$sname = $name . '_name_other';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'name_other' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	if ($name_other_disable) 
	{
		echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	}
	else
	{
		echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" disabled=\"true\" value=\"$value\">";
	}
	echo "<label $error id=\"$lid\" for=\"$sid\">Εκπαιδευτικό ίδρυμα (Άλλης χώρας, επιλέξτε χώρα εκτός Ελλάδας παραπάνω για να ενεργοποιήσετε)</label>";
	echo "</div>\n";

	
	$sid = $id . '_department';
	$sname = $name . '_department';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'department' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τμήμα σπουδών</label>";
	echo "</div>\n";


	$sid = $id . '_year_from';
	$sname = $name . '_year_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'year_from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Έτος εισαγωγής</label>";
	echo "</div>\n";


	$sid = $id . '_year_to';
	$sname = $name . '_year_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'year_to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Έτος αποφοίτησης</label>";
	echo "</div>\n";


	$sid = $id . '_final_grade';
	$sname = $name . '_final_grade';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'final_grade' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Βαθμός αποφοίτησης</label>";
	echo "</div>\n";


	$sid = $id . '_comments';
	$sname = $name . '_comments';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'comments' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea medium\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Σημειώστε συμπληρωματικά στοιχεία σε περίπτωση που το επιθυμείτε.</label>";
	echo "</div>\n";



	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;

}


function print_input_edu_further_array( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];
	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";

	$sid = $id . '_country';
	$sname = $name . '_country';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'country' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "
	
	";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 320px\" onChange=\"change_edu_subform('".$id."');\">";
	$res = mysql_query( "SELECT * from voc_countries" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'country' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
			$name_other_disable=true;
		}
		elseif ($data[ 'country' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
			if ($dbrow[ 'value' ]=="Ελλάδα") {$name_other_disable=false;} else {$name_other_disable=true;}
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 
    if (!($data[ 'country' ])) $name_other_disable=false;	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Χώρα σπουδών</label>";
	echo "</div>\n";
	
	$sid = $id . '_name';
	$sname = $name . '_name';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'name' ] );
	$lid = "label_$sid";
	$did = "div_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div id=\"".$did."\">";
	
	if (!($name_other_disable)) {
		echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\">";
	}
	else
	{
		echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 400px\" disabled=\"true\">";
	}	
	$res = mysql_query( "SELECT * from voc_universities_gr ORDER BY value ASC" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'name' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'name' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Εκπαιδευτικό ίδρυμα ΠΑΝΕΠΙΣΤΗΜΙΟ - ΤΕΙ (Επιλέξτε \"Ελλάδα\" για να εμφανιστούν )</label>";
	echo "</div>\n";	

	$sid = $id . '_name_other';
	$sname = $name . '_name_other';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'name_other' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";																		
	if ($name_other_disable)
	{
		echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\"  value=\"$value\">";
	}
	else
	{
		echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" disabled=\"true\" value=\"$value\">";
	}
	echo "<label $error id=\"$lid\" for=\"$sid\">Εκπαιδευτικό ίδρυμα άλλης χώρας (επιλέξτε χώρα εκτός Ελλάδας παραπάνω για να ενεργοποιήσετε)</label>";
	echo "</div>\n";

	
	$sid = $id . '_title';
	$sname = $name . '_title';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'title' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<select id=\"$sid\" name=\"$sname\" STYLE=\"width: 120px\">";
	$res = mysql_query( "SELECT * from voc_degree_further_types" );
	while ( ( $dbrow = mysql_fetch_assoc( $res ) ) !== false ) {
	    if (($dbrow[ 'option' ]=="selected") && !($data[ 'title' ])) {
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		}
		elseif ($data[ 'title' ]==$dbrow[ 'value' ]) 
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\" selected=\"selected\">";
		} 
		else
		{
			echo "<option value=\"".$dbrow[ 'value' ]."\">";
		}
		echo $dbrow[ 'value' ]."</option>";
	} 	
    echo "</select>";
	echo "<label $error id=\"$lid\" for=\"$sid\">Επίπεδο σπουδών</label>";
	echo "</div>\n";
		
	
	$sid = $id . '_department';
	$sname = $name . '_department';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'department' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τμήμα σπουδών</label>";
	echo "</div>\n";


	$sid = $id . '_year_from';
	$sname = $name . '_year_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'year_from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Έτος εισαγωγής</label>";
	echo "</div>\n";


	$sid = $id . '_year_to';
	$sname = $name . '_year_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'year_to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input type=\"text\" id=\"$sid\" name=\"$sname\" class=\"element text\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Έτος αποφοίτησης</label>";
	echo "</div>\n";


	$sid = $id . '_final_grade';
	$sname = $name . '_final_grade';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'final_grade' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Βαθμός αποφοίτησης</label>";
	echo "</div>\n";


	$sid = $id . '_comments';
	$sname = $name . '_comments';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'comments' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea medium\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Σημειώστε συμπληρωματικά στοιχεία σε περίπτωση που το επιθυμείτε.</label>";
	echo "</div>\n";

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;
}



function print_input_reco( $row, $count ) {
	$id = $row[ 'id' ] . "_$count";
	$name = $row[ 'name' ] . "_$count";
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	if ( !is_null( $data ) && array_key_exists( $count, $data ) ) $data = $data[ $count ];

	echo "<input type=\"hidden\" name=\"$name\" value=\"1\">\n";

	$sid = $id . '_lastname';
	$sname = $name . '_lastname';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'lastname' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Επίθετο</label>";
	echo "</div>\n";

	$sid = $id . '_name';
	$sname = $name . '_name';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'name' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Όνομα</label>";
	echo "</div>\n";

	$sid = $id . '_service';
	$sname = $name . '_service';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'service' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text large\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Υπηρεσία / Φορέας</label>";
	echo "</div>\n";

	$sid = $id . '_subject';
	$sname = $name . '_subject';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'subject' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea medium\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Αντικείμενο</label>";
	echo "</div>\n";

	$sid = $id . '_from';
	$sname = $name . '_from';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'from' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<small $error id=\"$lid\">Συνεργασία από:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";

	$sid = $id . '_to';
	$sname = $name . '_to';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'to' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<small> - </small><small $error id=\"$lid\">έως:</small> <input type=\"text\" id=\"$sid\" name=\"$sname\" size=\"4\" maxlength=\"4\" value=\"$value\">";
	echo "</div>\n";

	$sid = $id . '_coop_type';
	$sname = $name . '_coop_type';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'coop_type' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<textarea id=\"$sid\" name=\"$sname\" class=\"element textarea small\">";
	echo $value;
	echo "</textarea>\n";
	echo "<label $error id=\"$lid\" for=\"$sid\">Φύση Συνεργασίας</label>";
	echo "</div>\n";

	$sid = $id . '_phone';
	$sname = $name . '_phone';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'phone' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text small\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">Τηλέφωνο</label>";
	echo "</div>\n";

	$sid = $id . '_email';
	$sname = $name . '_email';
	$value = is_null( $data ) ? "" : htmlspecialchars( $data[ 'email' ] );
	$lid = "label_$sid";
	$error = ( in_array( $sid, $row[ 'invalid' ] ) ) ? 'class="validate"' : "";
	echo "<div>";
	echo "<input id=\"$sid\" name=\"$sname\" type=\"text\" class=\"element text medium\" value=\"$value\">";
	echo "<label $error id=\"$lid\" for=\"$sid\">E-mail</label>";
	echo "</div>\n";

	$min_count = array_key_exists( 'min_count', $row ) ? $row[ 'min_count' ] - 1 : 0;
	/*
	if ( $count > $min_count ) {
		$func = "on_btn_del_click( 'li_cand_reco_$count' );";
		echo "<input type=\"button\" id=\"btn_del_reco_$count\" onclick=\"$func\" value=\"Διαγραφή Σύστασης\">";
	}
	*/
}

function print_input_languages( $row ) {
	$langs = $row[ 'langs' ];
	$options = $row[ 'options' ];
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	echo "<table class=\"languages\">\n";
	foreach ( $langs as $code => $lang ) {
		$name = "lang_$code";
		echo "<tr>";
		echo "<td>$lang: </td>";
		echo "<td><select name=\"$name\">\n";
			foreach ( $options as $opcode => $option ) {
				$sel = '';
				if ( !is_null( $data ) && $data[ $code ] == $opcode ) {
					$sel = "selected";
				}
				echo "<option value=\"$opcode\" $sel>$option</option>\n";
			}
		echo "</select></td>\n";
		echo "</tr>\n";
	}
	echo "</table>\n";
}

function print_input_list( $row ,$lines_under) {
	$listitems = $row[ 'items' ];
	$options = $row[ 'options' ];
	$name = $row[ 'name' ];
	$width=$row[ 'select_width' ];
	$data = null;
	if ( array_key_exists( 'data', $row ) ) $data = $row[ 'data' ];
	echo "<select id=\"".$name."\" name=\"$name\" STYLE=\"width: ".$width."px\">\n";	
	for ($itemcounter=0;$itemcounter<count($listitems);$itemcounter++) {
		$name = "lang_$code";
		if ( array_key_exists( 'data', $row ) ) {
			if ($listitems[$itemcounter]==$data) {
				echo '<option value="'.$listitems[$itemcounter].'" selected="selected">'.$listitems[$itemcounter].'</option>';
			} else
			{
				echo '<option value="'.$listitems[$itemcounter].'">'.$listitems[$itemcounter].'</option>';
			}		
		} else
		{
			if ($options[$itemcounter]=="selected") {
				echo '<option value="'.$listitems[$itemcounter].'" selected="selected">'.$listitems[$itemcounter].'</option>';
			} else
			{
				echo '<option value="'.$listitems[$itemcounter].'">'.$listitems[$itemcounter].'</option>';
			}
		}

	}
    echo "</select>\n";
	for ($linescounter=0;$linescounter<$lines_under;$linescounter++)
	{
		print "<br>";
	}
}


function print_input( $row, $count  ) {
	$name = $row[ 'name' ];
	switch ( $row[ 'type' ] ) {
		case '#date':
			print_input_date( $row );
			break;
		case '#address':
			print_input_address( $row );
			break;
		case '#education':
			print_input_education( $row, $count );
			break;
		case '#languages':
			print_input_languages( $row );
			break;
		case '#reco':
			print_input_reco( $row, $count );
			break;
		case '#profession':
			print_input_profession( $row, $count );
			break;
		case '#prof_array':
			print_input_prof_array( $row, $count );
			break;
		case '#edu_array':
			print_input_edu_array( $row, $count );
			break;
		case '#edu_ba_array':
			print_input_edu_ba_array( $row, $count );
			break;
		case '#edu_further_array':
			print_input_edu_further_array( $row, $count );
			break;
		case 'itemlist':
			print_input_list( $row,$count);
			break;
		case '#lang_array':
		    print_input_lang_array( $row, $count );
			break;
		case '#lang_simple_array':
		    print_input_lang_simple_array( $row, $count );
			break;
		case '#computers_array':
		    print_input_computers_array( $row, $count );
			break;
		case '#yesno':
			$sel_yes = ( array_key_exists( 'data', $row ) && $row[ 'data' ] == '1' ) ? "checked" : "";
			$sel_no = ( array_key_exists( 'data', $row ) && $row[ 'data' ] == '0' ) ? "checked" : "";
			echo "<div>\n";
			echo "<input type=\"radio\" name=\"$name\" value=\"1\" $sel_yes>Ναι ";
			echo "<input type=\"radio\" name=\"$name\" value=\"0\" $sel_no>Όχι ";
			echo "</div>\n";
			break;
		case 'textarea':
			$id = $row[ 'id' ];
			$name = $row[ 'name' ];
			$value = "";
			if ( array_key_exists( 'data', $row ) ) $value = htmlspecialchars( $row[ 'data' ] );
			echo "<div>\n";
			if ( array_key_exists( 'size', $row ) ) {
				$areasize = $row[ 'size' ];
			}
			else $areasize = 'medium';
			echo "<textarea id=\"$id\" name=\"$name\" class=\"element textarea $areasize\">";
			if ( $value !== '' ) echo $value;
			echo "</textarea>\n";
			echo "</div>\n";
			break;
		default: 
			if ( array_key_exists( 'class', $row ) ) $myclass = $row[ 'class' ];
			else $myclass = "element text medium";
			$value = "";
			if ( array_key_exists( 'data', $row ) ) $value = htmlspecialchars( $row[ 'data' ] );
			print "<div>\n";
			print '<input type="' . $row[ 'type' ] . '" id="' . $row[ 'id' ] . '" name="' . $row[ 'name' ] . '" class="'. $myclass . '" value="' . $value . '">' . "\n";	
			print "</div>\n";
	}
}

function print_step( $data, $id, $count = null, $print_li = true,$lines_under=0 ) {
	global $suggest_other;
	if ( !array_key_exists( $id, $data ) ) error( "Invalid id: $id" );
	$row = $data[ $id ];
	$eid = $row[ 'id' ];
	$ename = $row[ 'name' ];
	if ( !is_null( $count ) ) {
		$eid = $eid . "_$count";
		$ename = $ename . "_$ename";
	}
	$li_id = "li_$eid";
	$lid = "label_$eid";
	$elabel = $row[ 'label' ];
	$error = false;
	
	if ( $print_li ) echo "<li id=\"$li_id\">\n";
	// Print label

	if (( array_key_exists( 'required', $row ) && $row[ 'required' ] ) 	)
	{
		if ( is_null( $count ) || ($count <= 1)) {
			if ( array_key_exists( 'min_count', $row )) 
			{
				if ($count<$row['min_count'])
				{
					$elabel = "$elabel (*)";
				}
			} else
			{
				$elabel = "$elabel (*)";
			}
		}
	}
	
	$error = ( in_array( $eid, $row[ 'invalid' ] ) ) ? "validate" : "";
	echo "<label id=\"$lid\" class=\"description $error\" for=\"$eid\">$elabel</label>\n";	
	// Print main input field
	print_input( $row, $count );
			for ($linescounter=1;$linescounter<$lines_under;$linescounter++)
			{
				print "<br>";
			}
	// Print guideline, if any	
	if ( array_key_exists( 'guide', $row ) && !is_null( $row[ 'guide' ] ) ) {
		if ( !array_key_exists( 'show_guide', $row ) || $row[ 'show_guide' ] ) {
			if ( is_null( $count ) || $count == 0 ) {
				if ( array_key_exists( 'guide_other', $row ) && $suggest_other )
				{
					$guide = $row[ 'guide_other' ];
				}
				else $guide = $row[ 'guide' ];
				echo "<p class=\"guidelines\"><small>$guide</small></p>\n";
			}
		}
	}
	if ( $print_li ) echo "</li>\n";
	return ( $error !== false ); 
}

function fill_data( &$data, &$arr ) {
	foreach ( $data as &$row ) {
		if ( !array_key_exists( 'name', $row ) ) continue;
		$name = $row[ 'name' ];
		switch ( $row[ 'type' ] ) {
			case '#date':
				$row[ 'data' ] = array(
					'day' => $arr[ $name . "_day" ],
					'month' => $arr[ $name . "_month" ],
					'year' => $arr[ $name . "_year" ] );
				break;
			case '#address':
				$row[ 'data' ] = array(
					'road' => $arr[ $name . "_road" ],
					'number' => $arr[ $name . "_number" ],
					//'dimos' => $arr[ $name . "_dimos" ],
					'city' => $arr[ $name . "_city" ],
					'country' => $arr[ $name . "_country" ],
					'areacode' => $arr[ $name . "_areacode" ],
					'region' => $arr[ $name . "_region" ],
					'municipality' => $arr[ $name . "_municipality" ]);
				break;
			case '#education':
			case '#profession':
			case '#reco':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#prof_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#edu_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#edu_ba_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#edu_further_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#lang_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#lang_simple_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#computers_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#npdd_npid_array':
				$row[ 'data' ] = array();
				foreach ( $arr as $k => $v ) {
					if ( strpos( $k, $name . "_" ) === 0 ) {
						$count = substr( $k, strlen( $name . "_" ) );
						if ( is_numeric( $count ) ) {
							$ename = $name . '_' . $count;
							$new_data = array();
							foreach ( $row[ 'children' ] as $child ) {
								$new_data[ $child ] = $arr[ $ename . "_" . $child ];
							}	
							$row[ 'data' ][ $count ] = $new_data;
						}
					}
				}
				break;
			case '#languages':
				$row[ 'data' ] = array();
				foreach ( $row[ 'langs' ] as $k => $v ) {
					$lang_id = "lang_$k";
					if ( array_key_exists( $lang_id, $arr ) ) {
						$row[ 'data' ][ $k ] = $arr[ $lang_id ];
					}
					else {
						$row[ 'data' ][ $k ] = '';
					}
				}
				break;

			case '#yesno':
				if ( array_key_exists( 'name', $row ) )
				{
					if ( array_key_exists( $row[ 'name' ], $arr ) ) {
						if ( array_key_exists( $name, $arr ) ) {
							$row[ 'data' ] = $arr[ $name ];
						}
					}
				}
				break;
			default: 
				if ( array_key_exists( $name, $arr ) ) {
					$row[ 'data' ] = $arr[ $name ];
				}
				break;
		}
	}
}

// Validation

function validate( &$data ) {
	global $suggest_other;
	$ok = true;

	$ok &= validate_captcha();
	foreach ( $data as &$row ) {
		if ( array_key_exists( 'requires_other', $row ) &&
		     $row[ 'requires_other' ] && !$suggest_other ) continue;
		$row[ 'invalid' ] = array();
		if ( !array_key_exists( 'required', $row ) || 
		     !$row[ 'required' ] ) continue;
		if ( array_key_exists( 'validate', $row ) && ($row[ 'validate' ]=="validate_vocation_other")) 	 
			if (validate_vocation_other($row,$data['cand_vocation'])) continue;
		
		if ( !array_key_exists( 'data', $row ) ) continue;
		if ( array_key_exists( 'children', $row ) ) {
			$ok &= validate_children( $row );
		}
		else {
			if ( !validate_single_row( $row ) ) {
				$row[ 'invalid' ][] = $row[ 'id' ];
				$ok = false;
				//var_dump( $row ); die();
			}
		}
	}
	return $ok;
}

function validate_captcha() {
	global $config;
	if ( !$config[ 'use_captcha' ] ) return true;
	if ( isset( $_SESSION[ 'captcha_ok' ] ) ) return true;
	require_once( 'lib/securimage/securimage.php' );
	$securimage = new Securimage();
	if ( $securimage->check( input( $_POST[ 'captcha_code' ] ) ) == false ) {
		return false;
	}
	$_SESSION[ 'captcha_ok' ] = true;
	return true;
}

function validate_email( $value ) {
	if(!eregi("^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$", $value)) {
		return false;
  	}
	return true;
}

function validate_day( $value ) {
	return $value >= 1 && $value <= 31;
}

function validate_month( $value ) {
	return $value >= 1 && $value <= 12;
}

function validate_year( $value ) {
	return $value >= 1900 && $value <= 2009;
}

function validate_areacode( $value ) {
	return strlen( $value ) == 5 && is_numeric( $value );
}

function validate_nonzero( $value ) {
	return $value != 0;
}
function validate_vocation_other( $otherdata,$listdata ) {
    if (($otherdata['data']=="" ) && ($listdata['data']==" Άλλο επάγγελμα (δεν υπάρχει στην λίστα)"))
	{
		$ok=false;
		if ($otherdata['data']=="" ) $otherdata[ 'invalid' ][] = $otherdata[ 'id' ];
		if ($listdata['data']==" Άλλο επάγγελμα (δεν υπάρχει στην λίστα)") $listdata[ 'invalid' ][] = $listdata[ 'id' ];
	} else 
	{
		$ok=true;
	}
	return $ok;
}

function validate_single_row( &$row ) {

	if ( is_array( $row[ 'data' ] ) && count( $row[ 'data' ] ) == 0 ) return false;
	if ( !is_array( $row[ 'data' ] ) && trim($row[ 'data' ]) === '' ) return false;
	if ( array_key_exists( 'validate', $row ) ) {
		$func = $row[ 'validate' ];
		if ( !$func( $row[ 'data' ] ) ) return false;
	}
	return true;
}

function validate_children( &$row ) {
	$ok = true;
	if ( !array_key_exists( 'data', $row ) ) return false;
	if ( !is_array( $row[ 'data' ] ) ) return false;

	if ( array_key_exists( 'multiple', $row ) && $row[ 'multiple' ] ) {
		foreach ( $row[ 'data' ] as $count => $data ) {
			if ( $count > ($row['min_count']-1) ) continue;
			$id = $row[ 'id' ] . '_' . $count;
			foreach ( $data as $k => $v ) {
				$eid = $id . '_' . $k;
				if ( trim( $v ) === '' ) {
					$row[ 'invalid' ][] = $eid;
					$ok = false;
				}
				if ( array_key_exists( 'validate', $row ) && 
					 array_key_exists( $k, $row[ 'validate' ] ) ) 
				{
					$func = $row[ 'validate' ][ $k ];
					if ( !$func( $v ) ) {
						$row[ 'invalid' ][] = $eid;
						$ok = false;
					}
				}
			}
		}

	}
	else {
		foreach ( $row[ 'data' ] as $k => $v ) {
			$eid = $row[ 'id' ] . '_' . $k;
			if ( trim( $v ) === '' ) {
				$row[ 'invalid' ][] = $eid;	
				$ok = false;
			}
			if ( array_key_exists( 'validate', $row ) &&
				 array_key_exists( $k, $row[ 'validate' ] ) ) 
			{
				$func = $row[ 'validate' ][ $k ];
				if ( !$func( $v ) ) {
					$row[ 'invalid' ][] = $eid;
					$ok = false;
				}
			}
		}
	}
	return $ok;
}

?>
