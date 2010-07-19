<?php

$ag_cidArray = $_POST["cid"];

if(!empty($ag_cidArray)){
	foreach($ag_cidArray as $ag_cidArrayKey => $ag_cidArrayValue){
		if(!empty($ag_cidArrayValue)){
			JFolder::delete(JPATH_SITE.'/plugins/content/AdmirorGallery/templates/'.$ag_cidArrayValue);
		}
	}
}

?>
