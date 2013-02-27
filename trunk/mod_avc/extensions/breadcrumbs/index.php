<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<span class="AVC_LAYOUT_SPAN AVC_LAYOUT_BREADCRUMPS">';
$AVC_count=1;
foreach ($AVC->state_history["module".$AVC->module_id] as $step) {
if($AVC_count==$AVC->state_history_count){
	echo $step["view_name"];
}else{
	echo '<a href="#" onclick="AVC_LAYOUT_GOTO(\''.$AVC->module_id.'\', '.$AVC_count.');">'.$step["view_name"].'</a> ► ';
}
$AVC_count++;
}
echo '</span>';
echo '<p>&nbsp;</p>';