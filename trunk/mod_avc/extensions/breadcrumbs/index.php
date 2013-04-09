<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<div class="breadcrumb AVC_LAYOUT_BREADCRUMPS"><div class="breadcrumbs">';
$AVC_count=1;
foreach ($AVC->state_history[$AVC->group] as $step) {

$opened = $step["opened"];
if( !empty($step["modules"][$opened]["view_name"]) ){
	$AVC_STEP_NAME = $step["modules"][$opened]["view_name"];
}else{
	$AVC_STEP_NAME = $step["modules"][$AVC->module_id]["view_name"];
}

if($AVC_count==$AVC->state_history_count){
	echo $AVC_STEP_NAME;
}else{
	echo '<a href="#" onclick="AVC_LAYOUT_GOTO(\''.$AVC->group.'\', '.$AVC_count.');" class="pathway">'.$AVC_STEP_NAME.'</a><img src="/www/skif/media/system/images/arrow.png" alt="">';
}
$AVC_count++;
}
echo '</div></div>';