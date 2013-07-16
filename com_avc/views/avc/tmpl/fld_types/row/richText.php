<?php

$form_items_width = 1;
if( !empty( $FIELD_PARAMS["width"] ) ){
	$form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 0;
if( !empty( $FIELD_PARAMS["height"] ) ){
	$form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
';

// Create Form Field Label
echo '<label id="AVC_frame jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';
//$editor =& JFactory::getEditor();
echo JFactory::getEditor()->display($FIELD_ALIAS, $FIELD_VALUE, '400', '500', '60', '10', false);
echo '<p>&nbsp;</p>';

echo '</div>';