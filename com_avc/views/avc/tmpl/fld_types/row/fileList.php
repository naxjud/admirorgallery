<?php

echo '<div class="form_items form_items1">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

$tmp_files = JFolder::files(JPATH_SITE.DS.$field_params);
echo '<select tabindex="'.$tabIndex.'" name="'.$field_alias.'" class="required editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_DROPLIST').'">';			
echo '<option value="">'.JText::_("COM_CCS_SELECT_NONE").'</option>';
foreach($tmp_files as $tmp_file)// Add Dropbox item for any param founded
{
	$selected=NULL;					
	if($tmp_file==$field_value)// Add Selected Value
	{
		$selected=' selected="selected"';
	}
echo '<option value="'.$tmp_file.'"'.$selected.'>'.$tmp_file.'</option>';
}
echo '</select>';

echo '</div>';