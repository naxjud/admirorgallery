<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

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


///////////////////////////////////////////////
//	ADD LABEL
///////////////////////////////////////////////
echo '
	<label id="jform_enabled-lbl" for="jform_enabled">
		' . JText::_( strtoupper($FIELD_TITLE)) . '
	</label>
';

///////////////////////////////////////////////
//	ADD INPUT
///////////////////////////////////////////////

echo
'
<div class="AVC_frame">
<input
	onkeyup="relOnChange(\'' . $FIELD_ALIAS . '\', $(this).get(\'value\'));"
	tabindex="' . $TABINDEX . '"
	type="text"
	id="' . $FIELD_ALIAS . '"
	name="' . $FIELD_ALIAS . '"
	value="' . $FIELD_VALUE . '"
	class="avc_rel width_auto"
	title="' . JText::_('COM_AVC_TOOLTIPS_VARCHAR') . '"
	size="4"
/>

<span
	id="lbl_' . $FIELD_ALIAS . '"
	style="text-overflow:ellipsis; overflow: hidden;display: block; white-space: nowrap;"
></span>

</div>
';
?>
<input
	type="button"
	class="pointer width_auto"
	value="<?php echo JText::_('COM_AVC_SELECT');?>"
	onclick="
		SqueezeBox.open(
			'index.php?option=com_avc&view=avc&layout=table&tmpl=component&HIDE_NOTES=true&target_field=<?php echo  $FIELD_ALIAS; ?>&curr_view_id=<?php echo $FIELD_PARAMS["queryId"]; ?>',
			{
			handler:'iframe',
			size: {
				x: 1000,
				y: 600,
			},
			onClose: function() {
		    }
		});
		return false;
	"
	>

<?php
echo
'	
<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_CLEAR').'"
	onclick="
		jInsertRelSelect(\'' . $FIELD_ALIAS . '\',\'\');
		return false;
	"
	>

';

//////////////////////////////////////////////
// END ITEM
//////////////////////////////////////////////
echo '
</div>
';

$REL_QUERY = $this->views[$FIELD_PARAMS["queryId"]]["query"];

$ROW_KEY = "id";
if($REL_QUERY["key"]){
    $ROW_KEY = $REL_QUERY["key"];
}

$ROWS = AvcModelAvc::execQuery($REL_QUERY);

$JS_FIELD_REL_VALUES='
//////////////////////////////
// FIELD REL DECLARE DOM READY
//////////////////////////////
window.addEvent("domready", function(){
AVC_REL[\'' . $FIELD_ALIAS . '\'] = new Object();
';

if(!empty($ROWS)){
	foreach($ROWS as $ROW){
		$JS_FIELD_REL_VALUES.='AVC_REL[\'' . $FIELD_ALIAS . '\'][\'' . $ROW[ $ROW_KEY ] . '\'] = "' . htmlspecialchars(implode(", ", $ROW)) . '";'."\n";
	}
}

$JS_FIELD_REL_VALUES.='
});
';

$this->doc->addScriptDeclaration($JS_FIELD_REL_VALUES);