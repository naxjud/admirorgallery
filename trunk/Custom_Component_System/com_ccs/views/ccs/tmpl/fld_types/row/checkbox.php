<?php

echo '<input type="hidden" id="'.$field_alias.'" name="'.$field_alias.'" value="'.$field_value.'" />';

// Field Label
echo '<div class="form_items">';    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

$checkboxArray = explode ( "," , $field_value );
$params_array = explode("\n",$field_params);
foreach($params_array as $params)// Add Dropbox item for any param founded
{
	$value__label = explode(",",$params);
	$checked=NULL;					
	if(in_array($value__label[0], $checkboxArray))// Add Selected Value
	{
		$checked='  checked="checked"';
	}
	echo '<input 
	type="checkbox" 
	onclick="
		cxb_change(\''.$field_alias.'\');
	" 
	class="width_auto cxb_'.$field_alias.'" 
	name="cxb_'.$field_alias.'[]" 
	value="'.$value__label[0].'"'.$checked.'/>
	<span class="inlineValue">'.JText::_($value__label[1]).'&nbsp;&nbsp;&nbsp;</span>
	<br />';
}				
echo '<br style="clear:both;" />';

echo '</div>';