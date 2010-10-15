<?php

$doc->addScript(JURI::root().'administrator/components/com_admirorgallery/scripts/jquery.jgrowl.js');
$doc->addStyleSheet(JURI::root().'administrator/components/com_admirorgallery/css/jquery.jgrowl.css');
$doc->addScriptDeclaration('

	function ag_showMessage(message,type) {
	      if(type == "notice"){
			  jQuery.jGrowl(message, { header: "'.JText::_( "NOTICE").'" });
	      }else{
			  jQuery.jGrowl(message, { header: "'.JText::_( "ERROR").'",theme: "error",sticky: true });

	      }
	}

');

?>
