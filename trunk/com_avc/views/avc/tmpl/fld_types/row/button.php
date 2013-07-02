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
echo JText::_(strtoupper($FIELD_ALIAS));
echo '</label>';

echo '<input type="button" class="pointer width_auto" onclick="' . $FIELD_PARAMS["onclick"] . '" value="' . JText::_( $FIELD_PARAMS["label"] ) . '" />';

echo '</div>';