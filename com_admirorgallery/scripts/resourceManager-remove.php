<?php

$ag_cidArray = $_POST["cid"];
$task = JRequest::getVar( 'task', null, 'post');

if(!empty($ag_cidArray)){
	foreach($ag_cidArray as $ag_cidArrayKey => $ag_cidArrayValue){
		if(!empty($ag_cidArrayValue)){
			if(JFolder::delete(JPATH_SITE.'/plugins/content/AdmirorGallery/'.$task.'/'.$ag_cidArrayValue)){
			      $ag_notice[] = Array ('Package removed.',$ag_cidArrayValue);
			}else{
			      $ag_error[] = Array ('Package cannot be removed.',$ag_cidArrayValue);
			}
		}
	}
}else{
    $ag_error[] = Array ('No package selected.');
}


?>
