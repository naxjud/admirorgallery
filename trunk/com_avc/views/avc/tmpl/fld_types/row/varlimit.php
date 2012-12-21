<?php


echo '
<div class="form_items">
';


///////////////////////////////////////////////
//	ADD LABEL
///////////////////////////////////////////////

echo '
<label id="jform_enabled-lbl" for="jform_enabled">
	' . JText::_( strtoupper($FIELD_TITLE)) . '
</label>
';

// Create value holder for post
echo '<input type="hidden" name="'.$FIELD_NAME.'" id="'.$FIELD_NAME.'" value="'.$FIELD_VALUE.'" />';

echo '<div class="varcharLimited"><span>'."\n";

$JS_FIELD_VARLIMIT='
//////////////////////////////
// FIELD VARLIMIT DECLARE DOM READY
//////////////////////////////
window.addEvent("domready", function(){
AVC_VARLIMIT["' . $FIELD_NAME . '"] = new Array();
';
for($i=0; $i<(int)$FIELD_PARAMS->length; $i++){
	$JS_FIELD_VARLIMIT.='AVC_VARLIMIT["'.$FIELD_NAME.'"]['.$i.']="'.substr($FIELD_VALUE, $i, 1).'";'."\n";
}
$JS_FIELD_VARLIMIT.='
});
';

$this->doc->addScriptDeclaration($JS_FIELD_VARLIMIT);

for($i=0; $i<(int)$FIELD_PARAMS->length; $i++){
$onkeyup = 'varcharLimited_validator(event,\''.$FIELD_NAME.'\','.$i.',this.value);';	     			
	echo '
	<input
		onkeyup="'.$onkeyup.'"
		tabindex="'.$TABINDEX.'"
		id="'.$FIELD_NAME.'_'.$i.'"
		type="text"
		value="'.substr($FIELD_VALUE, $i, 1).'"
		class=""
		title="'.JText::_('COM_AVC_TOOLTIPS_VARCHAR').'"
		size="1"
		maxlength="1"
	/>
	';
	$TABINDEX++;
}


echo '</span></div>'."\n";

echo
'
</div>
';