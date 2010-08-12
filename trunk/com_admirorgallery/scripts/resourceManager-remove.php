<?php

$ag_cidArray = $_POST["cid"];
$task = JRequest::getVar( 'task', null, 'post');

if(!empty($ag_cidArray)){
	foreach($ag_cidArray as $ag_cidArrayKey => $ag_cidArrayValue){
		if(!empty($ag_cidArrayValue)){
			if(JFolder::delete(JPATH_SITE.'/plugins/content/AdmirorGallery/'.$task.'/'.$ag_cidArrayValue)){
			      $msg = JText::_('Package removed.');
			      $msgType = "notice";
			}else{
			      $msg = JText::_('Package cannot be removed.');
			      $msgType = "error";
			}
			echo '<script type="text/javascript">ag_showMessage("'. $msg .'", "'.$msgType.'");</script>';
		}
	}
}


?>
