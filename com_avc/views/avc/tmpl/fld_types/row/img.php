<?php

if(!empty($FIELD_PARAMS["folder"])){
	$COM_AVC_IMG_FOLDER = $FIELD_PARAMS["folder"];
}else{
	$COM_AVC_IMG_FOLDER = "";
}

$onclick = '
FIELD_IMG_ID = \'' . $FIELD_ALIAS . '\';
SqueezeBox.fromElement(this, {
	handler:\'iframe\',
	size: {
		x: 800,
		y: 500,
	},
	url:\'index.php?option=com_media&amp;view=images&amp;tmpl=component&amp;asset=com_AVC&amp;author=&amp;fieldid=' . $FIELD_ALIAS . '&amp;folder=' . $COM_AVC_IMG_FOLDER . '\'
});
return false;
';

echo '
<div class="form_items form_items3">
';


///////////////////////////////////////////////
//	ADD LABEL
///////////////////////////////////////////////

echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';

///////////////////////////////////////////////
//	SHOW PREVIEW
///////////////////////////////////////////////

echo '<div class="imgFld_img_wrapp"	onclick="' . $onclick . '">';
if($FIELD_VALUE!=""){
	$imgFld_src=JURI::root().$FIELD_VALUE;	
}else{
	$imgFld_src=JURI::root().'administrator/components/com_avc/assets/images/no-image.png';
}
echo '<img src="' . $imgFld_src . '" id="img_' . $FIELD_ALIAS . '" />';	
echo "</div>";


///////////////////////////////////////////////
//	MANUAL INPUT
///////////////////////////////////////////////

echo '
	<input
		id="'.$FIELD_ALIAS.'"
		tabindex="' . $TABINDEX . '"
		type="text"
		name="' . $FIELD_ALIAS . '"
		value="' . $FIELD_VALUE . '"
		class=""
		title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
	/>
';

///////////////////////////////////////////////
//	BUTTONS SELECT & CLEAR
///////////////////////////////////////////////
echo '
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_SELECT').'" onclick="' . $onclick . '">			    
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_CLEAR').'"
	onclick="
		FIELD_IMG_ID = \'' . $FIELD_ALIAS . '\';
		jInsertFieldValue(\'\');
		return false;
	"
	>
';

echo '
</div>
';