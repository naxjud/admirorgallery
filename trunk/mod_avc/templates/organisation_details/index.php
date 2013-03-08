<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

if(!empty($AVC->output)){

JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/template.css' );
JFactory::getDocument()->addStyleSheet( JURI::root() . 'modules/mod_avc/templates/organisation_details/css/template.css' );

//var_dump($AVC->output);

echo '<hr class="AVC_SEPARATOR_LARGE" />';

echo '<h1>'.$AVC->output[0]["title"].'</h1>';

if(!empty($AVC->output[0]["article"])){
	echo '<p>'.$AVC->output[0]["article"].'</p>';
	echo '<p>&nbsp;</p>';	
}


// ADDRESS
echo '
<div class="AVC_SECTION_ADDRESS">
<p>'.$AVC->output[0]["address"].'</p>
<p>'.$AVC->output[0]["zip"].' '.$AVC->output[0]["town"].'</p>
<p>'.$AVC->output[0]["country"].'</p>
</div>
';

if(!empty($AVC->output[0]["phone"])){
// Parse JSON
$PHONE = json_decode($AVC->output[0]["phone"], true);
$AVC->checkJSON();
echo '<div class="AVC_SECTION_PHONE">';

foreach ($PHONE as $key => $value) {
	echo '<p>'.JText::_(strtoupper($key)).': '.$value.'</p>';
}

echo '</div>';
}

if(!empty($AVC->output[0]["e_mail"])){
echo '
<div class="AVC_SECTION_EMAIL">
<p><a href="mailto:'.$AVC->output[0]["e_mail"].'">'.$AVC->output[0]["e_mail"].'</a></p>
</div>
';
}

if(!empty($AVC->output[0]["web"])){
echo '
<div class="AVC_SECTION_WEB">
<p><a href="'.$AVC->output[0]["web"].'" target="blank">'.$AVC->output[0]["web"].'</a></p>
</div>
';
}


// INSERT PAGINATION
require dirname(dirname(dirname(__FILE__))) . DS . 'extensions' . DS . 'pagination'. DS .'index.php';

echo '<p style="clear:both">&nbsp;</p>';

}// if(!empty($AVC->output)){