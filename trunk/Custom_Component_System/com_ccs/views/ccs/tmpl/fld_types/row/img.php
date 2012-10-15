<?php

echo '<div class="form_items form_items3">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($field_alias));
echo '</label>';

// Show image preview
echo '<div class="imgFld_img_wrapp"
	onclick="
		imgFld_id=\''.$field_alias.'\';
		SqueezeBox.fromElement(this, {
			handler:\'iframe\',
			size: {
				x: 800,
				y: 500,
			},
			url:\'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_CCS&amp;author=&amp;fieldid='.$field_alias.'&amp;folder='.$field_params.'\'
		});
		return false;
	"
>';
if($field_value!=""){
	$imgFld_src=JURI::root().$field_value;	
}else{
	$imgFld_src=JURI::root().'administrator'.DS.'components'.DS.'com_ccs'.DS.'assets'.DS.'images'.DS.'no-image.png';
}
echo '<img src="'.$imgFld_src.'" id="img_'.$field_alias.'" />';	
echo "</div>";

echo '<input tabindex="'.$tabIndex.'" type="text" id="'.$field_alias.'" name="'.$field_alias.'" value="'.$field_value.'" title="'.JText::_('COM_CCS_TOOLTIPS_IMG').'" />';

// Show button
echo '
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_CCS_SELECT').'"
	onclick="
		imgFld_id=\''.$field_alias.'\';
		SqueezeBox.fromElement(this, {
			handler:\'iframe\',
			size: {
				x: 800,
				y: 500,
			},
			url:\'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_CCS&amp;author=&amp;fieldid='.$field_alias.'&amp;folder='.$field_params.'\'
		});
		return false;
	"
	>			    
';
echo '
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_CCS_CLEAR').'"
	onclick="
		imgFld_id=\''.$field_alias.'\';
		imgFld_url=\'\';
		imgFld_refresh();
		return false;
	"
	>
';

echo '</div>';