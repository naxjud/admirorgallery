<?php


echo '
<div class="form_items">
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
name="' . $FIELD_NAME . '" 
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
name="' . $FIELD_NAME . '" 
title="' . JText::_('COM_AVC_TOOLTIPS_TINYINT') . '" 
value="0"' . $selected_f . '/>
<span class="inlineValue">' . JText::_('COM_AVC_FALSE') . '&nbsp;&nbsp;&nbsp;</span>
<br />
';

echo '
</div>
';





