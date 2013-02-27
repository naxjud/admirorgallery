<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/template.css' );
JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/default/css/template.css' );

//var_dump($AVC->output);

// INSERT BREADCRUMBS
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'breadcrumbs'. DS .'index.php';



foreach ($AVC->output as $rowIndex => $rowContent) {

	foreach ($rowContent as $fieldAlias => $fieldValue) {
		if($fieldAlias!="id"){
			echo '<h3>'.$fieldAlias.'</h3>';
			echo $fieldValue;
			echo '<hr />'."\n";		
		}	
	}
}


// INSERT PAGINATION
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'pagination'. DS .'index.php';

echo '<p style="clear:both">&nbsp;</p>';
