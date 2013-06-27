<?php

// Field Label
echo '<div class="form_items">';    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

echo '
	<input
		type="hidden"
		name="' . $FIELD_ALIAS . '"
		id="' . $FIELD_ALIAS . '"
		value="' . htmlspecialchars($FIELD_VALUE) . '"
		class=""
	/>
	';

$checkboxArray = explode ( "," , $FIELD_VALUE );

foreach($FIELD_PARAMS as $VALUE => $LABEL)// Add Dropbox item for any param founded
{

	$checked=NULL;					
	if(in_array($VALUE, $checkboxArray))// Add Selected Value
	{
		$checked='  checked="checked"';
	}
	echo '<input 
	type="checkbox"
	onchange="JS_FIELD_CBX(\''.$FIELD_ALIAS.'\')"
	class="width_auto"
	id="cxb_'.$FIELD_ALIAS.'" 
	name="cxb_'.$FIELD_ALIAS.'"
	value="'.$VALUE.'"'.$checked.'/>
	<span class="inlineValue">'.JText::_( $LABEL ).'&nbsp;&nbsp;&nbsp;</span>
	<br />';
}				

echo '<br style="clear:both;" />';

echo '</div>';