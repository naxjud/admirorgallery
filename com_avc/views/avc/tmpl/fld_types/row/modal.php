<?php

$form_items_width = 1;
if( !empty( $FIELD_PARAMS["width"] ) ){
	$form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 1;
if( !empty( $FIELD_PARAMS["height"] ) ){
	$form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
'; 
  
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

$width__height__url = explode("|",$field_params);
echo '<input tabindex="'.$tabIndex.'" type="text" id="'.$FIELD_ALIAS.'" name="'.$FIELD_ALIAS.'" value="'.$FIELD_VALUE.'" class="width_auto required validate-text editlinktip" />';
echo '<input type="button" class="pointer width_auto" value="'.JText::_('COM_CCS_SELECT').'" onclick="SqueezeBox.fromElement(this, {handler:\'iframe\', size: {x: '.$width__height__url[0].', y: '.$width__height__url[1].'}, url:\''.urlencode($width__height__url[2]).'\'})">'; 

echo '</div>';