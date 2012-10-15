<?php
 
// No direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

foreach($CCS->output as $row){
	if($row['published']=="1"){
	echo '
	<a href="'.$row['url'].'" class="ccs_partners_item" target="_blank">
<img src="'.JURI::base().DS.$row['image'].'" />
<div>'.$row['name'].'</div>
	</a>
	';
	}
}

echo '<br style="clear:both;" />';
