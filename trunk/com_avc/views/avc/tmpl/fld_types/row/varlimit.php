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
echo '<input type="hidden" name="'.$FIELD_ALIAS.'" id="'.$FIELD_ALIAS.'" value="'.$FIELD_VALUE.'" />';

echo '<div class="varcharLimited"><span>'."\n";

$JS_FIELD_VARLIMIT='
//////////////////////////////
// FIELD VARLIMIT DECLARE DOM READY
//////////////////////////////
window.addEvent("domready", function(){
AVC_VARLIMIT["' . $FIELD_ALIAS . '"] = new Array();
';
for($i=0; $i<(int)$FIELD_PARAMS["length"]; $i++){
	$JS_FIELD_VARLIMIT.='AVC_VARLIMIT["'.$FIELD_ALIAS.'"]['.$i.']="'.substr($FIELD_VALUE, $i, 1).'";'."\n";
}
$JS_FIELD_VARLIMIT.='
});
';

$this->doc->addScriptDeclaration($JS_FIELD_VARLIMIT);

for($i=0; $i<(int)$FIELD_PARAMS["length"]; $i++){
$onkeyup = 'varcharLimited_validator(event,\''.$FIELD_ALIAS.'\','.$i.',this.value);';	     			
	echo '
	<input
		onkeyup="'.$onkeyup.'"
		tabindex="'.$TABINDEX.'"
		id="'.$FIELD_ALIAS.'_'.$i.'"
		type="text"
		value="'.substr($FIELD_VALUE, $i, 1).'"
		class="AVC_VARLIMIT_INPUT"
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