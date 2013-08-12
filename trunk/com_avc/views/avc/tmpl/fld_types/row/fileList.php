<?php

if(!empty($FIELD_PARAMS["folder"])){
	$COM_AVC_FOLDER = $FIELD_PARAMS["folder"];
}else{
	$COM_AVC_FOLDER = "";
}

$form_items_width = 1;
if( !empty( $FIELD_PARAMS["width"] ) ){
	$form_items_width = $FIELD_PARAMS["width"];
}
$form_items_height = 2;
if( !empty( $FIELD_PARAMS["height"] ) ){
	$form_items_height = $FIELD_PARAMS["height"];
}

echo '
<div class="form_items form_item_width_'. $form_items_width .' form_item_height_'. $form_items_height .'">
';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';


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
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_SELECT').'" onclick="
		SqueezeBox.fromElement($(\'' . $FIELD_ALIAS . '_wrap\'), {
			handler:\'clone\',
			size: {
				x: 400,
				y: 400,
			}
		});
		return false;
	"
	>			    
	<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_CLEAR').'"
	onclick="
		avc_filelist_update(\'' . $FIELD_ALIAS . '\', \'\', \'\');
		return false;
	"
	>
';

//////////////////////////////////////////////
// CREATE POPUP WINDOW
//////////////////////////////////////////////
echo '
<div style="display:none;">
<div id="' . $FIELD_ALIAS . '_wrap">
<h1>' . JText::_( strtoupper($FIELD_TITLE)) . '</h1>

<ul>
';

$COM_AVC_FOLDER_CONTENT = JFolder::files(JPATH_SITE.DS.$COM_AVC_FOLDER);
foreach($COM_AVC_FOLDER_CONTENT as $COM_AVC_FILE){
	echo '
	<li>
		<a
			href="#"
			onclick="
				window.parent.avc_filelist_update(\'' . $FIELD_ALIAS . '\', \'' . $COM_AVC_FOLDER . '\', \'' . $COM_AVC_FILE . '\');
				window.parent.SqueezeBox.close();
				return false;
			"
		>
		' . $COM_AVC_FILE . '
		</a>
	</li>
	';
}

echo '
</ul>
</div>
</div>
';












echo '</div>';