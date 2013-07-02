<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

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

echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
	<input
		tabindex="' . $TABINDEX . '"
		type="text"
		name="' . $FIELD_ALIAS . '"
		value="' . htmlspecialchars($FIELD_VALUE) . '"
		class=""
		title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
	/>
</div>
';