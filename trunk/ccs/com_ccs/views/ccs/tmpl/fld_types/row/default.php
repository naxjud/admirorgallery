<?php

echo '<div class="form_items form_items1">';    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';
echo '<input tabindex="'.$tabIndex.'" type="text" name="'.$field_alias.'" value="'.$field_value.'" class="required validate-text editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_VARCHAR').'" />';
echo '</div>';