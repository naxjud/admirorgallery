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

///////////////////////////////////////////////
//	ADD LABEL
///////////////////////////////////////////////
echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';


///////////////////////////////////////////////
//	ADD RADIO
///////////////////////////////////////////////

$selected_f = NULL;
$selected_t = NULL;
if ($FIELD_VALUE == "0") {
    $selected_f = ' checked="checked"';
} else {
    $selected_t = ' checked="checked"';
}

// --- Mark selected
echo '
<input tabindex="' . $TABINDEX . '" 
type="radio" 
class="width_auto" 
name="' . $FIELD_ALIAS . '" 
title="' . JText::_('COM_AVC_TOOLTIPS_TINYINT') . '" 
value="1"' . $selected_t . '/>
<span class="inlineValue">' . JText::_('COM_AVC_TRUE') . '&nbsp;&nbsp;&nbsp;</span>
<br />
';

$TABINDEX++;

echo '
<input tabindex="' . $TABINDEX . '" 
type="radio" 
class="width_auto" 
name="' . $FIELD_ALIAS . '" 
title="' . JText::_('COM_AVC_TOOLTIPS_TINYINT') . '" 
value="0"' . $selected_f . '/>
<span class="inlineValue">' . JText::_('COM_AVC_FALSE') . '&nbsp;&nbsp;&nbsp;</span>
<br />
';

echo '
</div>
';





