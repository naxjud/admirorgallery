<?php

echo '<div class="form_items form_items1">';  

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

echo '<select tabindex="'.$tabIndex.'" name="'.$FIELD_ALIAS.'" class="required editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_DROPLIST').'">';			
echo '<option value="">'.JText::_("COM_CCS_SELECT_NONE").'</option>';
$params_array = explode("\n",$FIELD_PARAMS);
foreach($params_array as $params)// Add Dropbox item for any param founded
{
	$value__label = explode(",",$params);
	$selected=NULL;					
	if($value__label[0]==$field_value)// Add Selected Value
	{
		$selected=' selected="selected"';
	}
echo '<option value="'.$value__label[0].'"'.$selected.'>'.JText::_($value__label[1]).'</option>';
}
echo '</select>';

echo '</div>';