<?php

echo '<input type="hidden" id="'.$FIELD_ALIAS.'" name="'.$FIELD_ALIAS.'" value="'.$FIELD_VALUE.'" />';

// Field Label
echo '<div class="form_items">';    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

$checkboxArray = explode ( "," , $FIELD_VALUE );
$params_array = explode("\n",$FIELD_PARAMS);
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
		cxb_change(\''.$FIELD_ALIAS.'\');
	" 
	class="width_auto cxb_'.$FIELD_ALIAS.'" 
	name="cxb_'.$FIELD_ALIAS.'[]" 
	value="'.$value__label[0].'"'.$checked.'/>
	<span class="inlineValue">'.JText::_($value__label[1]).'&nbsp;&nbsp;&nbsp;</span>
	<br />';
}				
echo '<br style="clear:both;" />';

echo '</div>';