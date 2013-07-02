<?php

$form_items_width = 1;
if( !empty( $FIELD_PARAMS["width"] ) ){
	$form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 1;
if( !empty( $FIELD_PARAMS["height"] ) ){
	$form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
'; 
  
// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_TITLE));
echo '</label>';

echo '<input tabindex="' . $TABINDEX . '" id="' . $FIELD_ALIAS . '" name="' . $FIELD_ALIAS . '" type="text" class="width_auto" value="' . $FIELD_VALUE . '" />';
echo '<input type="button" id="' . $FIELD_ALIAS . '_btn" class="pointer width_auto calendar" value="'.JText::_('COM_AVC_SELECT').'" title="'.JText::_('COM_AVC_TOOLTIPS_DATE').'">'; 
echo '
<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_CLEAR').'"
	onclick="
		$(\'' . $FIELD_ALIAS . '\').value = \'0000-00-00\';
		return false;
	"
	>
	';

echo '
<script  type="text/javascript">
window.addEvent(\'domready\', function() {
	Calendar.setup({
        inputField     :    "' . $FIELD_ALIAS . '",     // id of the input field
        ifFormat       :    "%Y-%m-%d",      // format of the input field
        button         :    "' . $FIELD_ALIAS . '_btn",  // trigger for the calendar (button ID)
        align          :    "Bl",           // alignment (defaults to "Bl" = Bottom Left, 
// "Tl" = Top Left, "Br" = Bottom Right, "Bl" = Botton Left)
        singleClick    :    true
    });});
</script>
';

echo '</div>';

