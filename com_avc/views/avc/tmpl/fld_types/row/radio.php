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
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';


foreach($FIELD_PARAMS as $VALUE => $LABEL)// Add Dropbox item for any param founded
{						
		
	$checked="";
	if($VALUE==$FIELD_VALUE)// Add Selected Value
	{
		$checked='  checked="checked"';
	}
	echo '<input type="radio" class="width_auto" name="'.$FIELD_ALIAS.'" value="'.$VALUE.'"'.$checked.'/><span class="inlineValue">'.JText::_($LABEL).'&nbsp;&nbsp;&nbsp;</span><br />';

}

echo '<br style="clear:both;" />';

echo '</div>';