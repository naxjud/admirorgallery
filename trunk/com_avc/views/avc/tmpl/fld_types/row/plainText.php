<?php

echo '<div class="form_items">';

// Create Form Field Label
echo '<label id="jform_enabled-lbl" for="jform_enabled">';
echo JText::_( strtoupper($FIELD_ALIAS));
echo '</label>';

echo '<textarea tabindex="'.$TABINDEX.'" wrap="off" name="'.$FIELD_ALIAS.'" id="'.$FIELD_ALIAS.'" rows="20" class="required validate-text editlinktip">'.$FIELD_VALUE.'</textarea>';

echo '</div>';



$JS_FIELD_TEXTAREA_TAB= '

///////////////////////////////////////////
// FIELD_TEXTAREA_TAB
///////////////////////////////////////////

//////////////////////////////
// DECLARE DOM READY
//////////////////////////////

window.addEvent("domready", function(){

	var indentor = new MooIndent($("'.$FIELD_ALIAS.'"));

});


';

$this->doc->addScriptDeclaration($JS_FIELD_TEXTAREA_TAB);

