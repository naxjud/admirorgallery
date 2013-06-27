<?php

echo '<div class="form_items">';
    
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';


foreach($FIELD_PARAMS as $VALUE => $LABEL)// Add Dropbox item for any param founded
{						
		
	$checked="";
	if($VALUE==$FIELD_VALUE)// Add Selected Value
	{
		$checked='  checked="checked"';
	}
	echo '<input type="radio" class="width_auto" name="'.$FIELD_ALIAS.'" value="'.$VALUE.'"'.$checked.'/><span class="inlineValue">'.JText::_($LABEL).'&nbsp;&nbsp;&nbsp;</span><br />';

}

echo '<br style="clear:both;" />';

echo '</div>';