<?php

echo '<div class="form_items form_items1">';  
  
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

echo '<input tabindex="'.$tabIndex.'" id="entrydate" name="'.$field_alias.'" type="text" class="required validate-text width_auto" value="'.$field_value.'" />';
$tabIndex++;
echo '<input type="button" id="entrydate_img" class="pointer width_auto calendar" value="'.JText::_('COM_CCS_SELECT').'" onclick="" title="'.JText::_('COM_CCS_TOOLTIPS_DATE').'">'; 

echo '</div>';