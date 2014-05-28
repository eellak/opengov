<?php 
/**
 * @description Paradoteo Δράση 4 - Διαχείριση ηλεκτρονικών προσκλήσεων στελέχωσης μετακλητών θέσεων main page
 * @author Fotis Routsis <fotis.routsis@gmail.com>
 * @package drash4
 * @license http://joinup.ec.europa.eu/software/page/eupl EUPL Licence
 */
	global  $is_index;
	$is_index = true; 	// For sidebar use
	
	require_once('themes/header.php'); 
	require_once("manage/global.php");
?>
	<div class="page-header">
		<h1>Κατάλογος Προσκλήσεων</h1>
    </div>
    <?php
    //This is the number of results displayed per page
    $page_rows = 10;
    //This checks to see if there is a page number. If not, it will set it to page 1
    $pagenum=$_GET['pagenum'];
    if (!(isset($pagenum)))
       	$pagenum = 1;
    
        $sql = "SELECT ps.`option` as code,ps.prosklisi as prosklisi , count(p.active)  = sum(p.active) as status,p.office_parent,ps.date_start,ps.date_end, p.active FROM
        `positions_subcategory` as ps , `positions` as p WHERE ps.`option`= p.`option` group by ps.`option`  order by ps.id desc";

        $result=mysql_query($sql) or die(mysql_error());
		$rows = mysql_num_rows($result);
		if($rows>0){
			?>
    <div id="mybox"></div>
	<table class="table table-striped">
              <thead>
                <tr>
				 <th>Κατάσταση</th>
					<th>Tίτλος Πρόσκλησης</th>
                  <th>Ημερομηνία Δημοσίευσης</th>
                  <th>Καταληκτική Ημερομηνία</th>
                </tr>
              </thead>
              <tbody>

    
	<?php 	
		//This tells us the page number of our last page
		$last = ceil($rows/$page_rows);
		//this makes sure the page number isn't below one, or more than our maximum pages
		
		if ($pagenum < 1)
			$pagenum = 1;
				
		elseif ($pagenum > $last)
			$pagenum = $last;
		
		//This sets the range to display in our query
		$max = 'limit ' .($pagenum - 1) * $page_rows .',' .$page_rows;
		
		$sql = "SELECT ps.`option` as code,ps.prosklisi as prosklisi , count(p.active)  = sum(p.active) as status,p.office_parent,ps.date_start,ps.date_end, p.active FROM
		`positions_subcategory` as ps , `positions` as p WHERE ps.`option`= p.`option` group by ps.`option` order by ps.id desc ". $max;
		
		$result=mysql_query($sql) or die(mysql_error());
        while($row = mysql_fetch_row ($result)){
			
        	if($row[6]==1) { 
				$outlink_format="<a href='".SITE_URL."view.php?option={0}'>{1}</a>";
				$viewItem=StringFormat($outlink_format, $row[0],$row[3]);
				$status='<span class="label label-success">Ανοικτή</span>';
			} else {
				$viewItem= $row[3];
				$status='<span class="label">Κλειστή</span>';
			}
            echo"<tr>";
			echo"<td>".$status."</td>";
			echo"<td>".$viewItem." </td>";
            echo"<td>".$row[4]."</td>";
            echo"<td>".$row[5]."</td>";
            echo"</tr>";
        }
        
        
    ?>
              </tbody>
            </table>

<div class="pagination">
 	 <ul>
 	 <?php
 	 if($pagenum>1){
		$previous = $pagenum-1;
		echo "<li><a href=\"?pagenum=".$previous."\">Προηγούμενη</a></li>";
 	 }
 	 	for($i=1;$i<=$last;$i++){
 	 		if($i==$pagenum)
 	 			echo "<li class=\"active\"><a href=\"#\">$i</a></li>";
 	 		else	 
 	 			echo "<li><a href=\"?pagenum=".$i."\">$i</a></li>";
 	 	}
 	 	
 	 	if($pagenum<$last){
 	 		$next = $pagenum+1;
 	 		echo "<li><a href=\"?pagenum=".$next."\">Επόμενη</a></li>";
 	 	}
 	 ?>
    	
  	</ul>
</div>

<?php }else{
		
		echo"<div class=\"alert alert-block\">\n";
		echo"<h4>Κανένα αποτέλεσμα</h4>\n";
		echo"Δεν υπάρχουν διαθέσιμες ανοικτές προσκλήσεις προς το παρόν.\n";
		echo"</div>\n";
	}?>
<?php require_once('themes/footer.php'); ?>