<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

///////////////////////////////////////////////
//	CREATE LISTING
///////////////////////////////////////////////
$dbObject = JFactory::getDBO();
$query = $dbObject->getQuery(true);
$query->select( array( $FIELD_PARAMS["select"] ) );
$query->from( $FIELD_PARAMS["from"] );

// WHERE
// commented because it need all to be listed
// if(!empty($FIELD_PARAMS["where"])){
//     foreach ($FIELD_PARAMS["where"] as $value) {
//      	$value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
//         $query->where($value);
//     }
// }

// HAVING
if(!empty($FIELD_PARAMS["having"])){
    foreach ($FIELD_PARAMS["having"] as $value) {    	
		if(!is_numeric($FIELD_VALUE)){
     		$value = str_replace("FIELD_VALUE", $dbObject->Quote($FIELD_VALUE), $value);
		}else{
     		$value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
		}
        $query->where($value);
    }
}

// LEFT JOIN
if(!empty($FIELD_PARAMS["left_join"])){
    foreach ($FIELD_PARAMS["left_join"] as $value) { 
        $query->leftJoin($value);
    }
}

// ORDER
if(!empty($FIELD_PARAMS["order_by"])){
    $query->order($FIELD_PARAMS["order_by"]);
}

$dbObject->setQuery($query);
$ROWS = $dbObject->loadAssocList();

//////////////////////////////////////////////
// START ITEM
//////////////////////////////////////////////
echo '
<div class="form_items form_items2">
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
<span id="lbl_' . $FIELD_ALIAS . '"></span>
</div>
';

///////////////////////////////////////////////
//	ADD BUTTON
///////////////////////////////////////////////
echo
'
<input type="button" class="pointer width_auto" value="'.JText::_('COM_AVC_SELECT').'"
	onclick="
		SqueezeBox.fromElement($(\'' . $FIELD_ALIAS . '_wrap\'), {
			handler:\'clone\',
			size: {
				x: 400,
				y: 600,
			}
		});
		return false;
	"
	>
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



//////////////////////////////////////////////
// CREATE POPUP WINDOW
//////////////////////////////////////////////
echo '
<div style="display:none;">
<div id="' . $FIELD_ALIAS . '_wrap">
<h1>' . JText::_( strtoupper($FIELD_TITLE)) . '</h1>

<ul>
';

$JS_FIELD_REL_VALUES='

//////////////////////////////
// FIELD REL DECLARE DOM READY
//////////////////////////////
window.addEvent("domready", function(){
AVC_REL[\'' . $FIELD_ALIAS . '\'] = new Object();
';
if(!empty($ROWS)){

foreach($ROWS as $ROW){
	$JS_FIELD_REL_VALUES.='AVC_REL[\'' . $FIELD_ALIAS . '\'][\'' . $ROW[ $FIELD_PARAMS["key"] ] . '\'] = "' . htmlspecialchars(implode(", ", $ROW)) . '";'."\n";
}

$JS_FIELD_REL_VALUES.='



});
';

foreach($ROWS as $ROW){
	echo '
	<li>
		<a
			href="#"
			onclick="
				window.parent.jInsertRelSelect(\'' . $FIELD_ALIAS . '\', \'' . $ROW[ $FIELD_PARAMS["key"] ] . '\');
				window.parent.SqueezeBox.close();
				return false;
			"
		>
		' . implode(", ", $ROW) . '
	</li>
	';
}

}

$this->doc->addScriptDeclaration($JS_FIELD_REL_VALUES);
