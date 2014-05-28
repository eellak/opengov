<?php 
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων view the office.
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
global $is_single ;
$is_single = true;

require_once('themes/header.php'); 
include_once("manage/global.php"); ?>

<?php 

$option = mysql_real_escape_string($_GET['option']);
$format = "SELECT date_end,date_start,description,	max_selection from positions_subcategory where `option`='{0}' order by id desc";
$str_insert1=StringFormat($format, $option);
$result = mysql_query($str_insert1) or die(mysql_error());
$row = mysql_fetch_row ($result);
$dateend = $row[0];
$datestart = $row[1];
$desc= $row[2];
$maxsel = $row[3];

$format = "select office_parent,foreas_name,foreas_position,active from positions where `option`='{0}'";

$str_insert1=StringFormat($format, $option);
$result = mysql_query($str_insert1) or die(mysql_error());
$num_rows = mysql_num_rows($result);
$k=0;
while($row = mysql_fetch_object ($result)) {
	$foreas_name = $row->foreas_name;
	$title = $row->office_parent;
	$foreas_position = explode(')', $row->foreas_position);
	$active = $row->active;
	if(!isset($foreas_position[1])) {
		$data[$k]['position'] = $row->foreas_position;
		$data[$k]['posnum'] =1;
		
	}else{		
		$foreas_position = explode('(', $foreas_position[0]);
		$data[$k]['position'] = $foreas_position[0];
		$data[$k]['posnum'] = $foreas_position[1];
	}
	$k++;
}

if($active==1) $status='<span class="label label-success">Ανοικτή</span>';
else $status='<span class="label">Κλειστή</span>';
?>
	<div class="page-header">
    	<h2><small><?php echo $title;?></small></h2>
    </div>
    <?php 
    echo"<div class=\"well\">\n";
$outlink_format='<br/><a  class="btn btn-primary btn-small" href="'.SITE_URL.'select_office.php?option={0}" target="_blank">Δήλωση Συμμετοχής</a>';
$outlink=StringFormat($outlink_format,$option  , $title);
echo $status."<br/><br/>";
echo "<strong>Ημερομηνία Δημοσίευσης</strong>:". $datestart."<br/>";
echo "<strong>Ημερομηνία Ολοκλήρωσης</strong>:". $dateend."<br/>";
if($active==1)
	echo $outlink;
echo"</div>\n";
echo"<br/><br/>";
echo $desc;

echo"<br/><br/>";
echo '<a href="'.SITE_URL.'"><button class=\"btn\">Επιστροφή</button></a>';
echo"<br/><br/>";
    ?>

<?php require_once('themes/footer.php'); ?>    