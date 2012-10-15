<?php

echo '<div class="form_items">';
    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

$checked_item=0;
$params_array = explode("\n",$field_params);	
foreach($params_array as $i => $params)// Add Dropbox item for any param founded
{						
	$value__label = explode(",",$params);
	if($value__label[0]==$field_value)// Add Selected Value
	{
		$checked_item=$i;
	}
}
foreach($params_array as $i => $params)// Add Dropbox item for any param founded
{		
	$value__label = explode(",",$params);
	$checked="";
	if($checked_item==$i)// Add Selected Value
	{
		$checked='  checked="checked"';
	}
	echo '<input type="radio" class="width_auto" name="'.$field_alias.'" value="'.$value__label[0].'"'.$checked.'/><span class="inlineValue">'.JText::_($value__label[1]).'&nbsp;&nbsp;&nbsp;</span><br />';
}
echo '<br style="clear:both;" />';

echo '</div>';