<?php

echo '<div class="form_items form_items1">'."\n";   

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>'."\n";

// Create value holder for post
echo '<input type="hidden" name="'.$field_alias.'" id="'.$field_alias.'" value="'.$field_value.'" />';
echo '<div class="varcharLimited"><span>'."\n";
// Create JS array to keep current values
$varcharLimited_array='varcharLimited_array["'.$field_alias.'"]=new Array();';
for($i=0; $i<(int)$field_params; $i++){
	// Populate JS array
	$varcharLimited_array.='
	varcharLimited_array["'.$field_alias.'"]['.$i.']="'.substr($field_value, $i, 1).'";
';
$onkeyup = 'varcharLimited_validator(event,\''.$field_alias.'\','.$i.',this.value);';	     			
echo '<input tabindex="'.$tabIndex.'" id="'.$field_alias.'_'.$i.'" type="text" onkeyup="'.$onkeyup.'" value="'.substr($field_value, $i, 1).'" class="required validate-text editlinktip" title="'.JText::_('COM_CCS_TOOLTIPS_VARCHAR').'" size="1" maxlength="1" />';
	$tabIndex++;
}
echo '</span></div>'."\n";

echo '</div>';