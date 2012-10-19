<?php

echo '<div class="form_items">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_(strtoupper($field_alias));
echo '</label>';

$selected_f = NULL;
$selected_t = NULL;
if ($field_value == "0") {
    $selected_f = ' checked="checked"';
} else {
    $selected_t = ' checked="checked"';
}
// --- Mark selected
echo '
<input tabindex="' . $tabIndex . '" 
type="radio" 
class="width_auto" 
name="' . $field_alias . '" 
title="' . JText::_('COM_CCS_TOOLTIPS_TINYINT') . '" 
value="1"' . $selected_t . '/>
<span class="inlineValue">' . JText::_('COM_CCS_TRUE') . '&nbsp;&nbsp;&nbsp;</span>
<br />
';
$tabIndex++;
echo '
<input tabindex="' . $tabIndex . '" 
type="radio" 
class="width_auto" 
name="' . $field_alias . '" 
title="' . JText::_('COM_CCS_TOOLTIPS_TINYINT') . '" 
value="0"' . $selected_f . '/>
<span class="inlineValue">' . JText::_('COM_CCS_FALSE') . '&nbsp;&nbsp;&nbsp;</span>
<br />
';

echo '<br style="clear:both;" />';

echo '</div>';