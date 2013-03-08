<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

if(!empty($AVC->output)){

JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/template.css' );
JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/administration/css/template.css' );

//var_dump($AVC->output);

echo '<hr class="AVC_SEPARATOR_LARGE" />';

echo '<h1>'.JText::_("ADMINISTRATION").'</h1>';


$committeeNames = array();
$newOutput = array();
// COLLECT ALL COMMITTEE NAMES
foreach ($AVC->output as $key => $value) {

	$newOutput[$value["committee_name"]][] = $value;

}

foreach ($newOutput as $committeeName => $committees) {

	echo '<h2>'.$committeeName.'</h2>';

	foreach ($committees as $committe) {	
		echo '<h3>'.$committe["function_name"].'</h3>';
		echo '<p>'.$committe["last_name"].' '.$committe["first_name"].'</p>';
		echo '<p>&nbsp;</p>';
	}

	echo '<hr class="AVC_SEPARATOR" />';

}	





echo '<p style="clear:both">&nbsp;</p>';

}// if(!empty($AVC->output)){