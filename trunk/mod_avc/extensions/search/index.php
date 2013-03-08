<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<span class="AVC_LAYOUT_EXT">';

$AVC_search_options = array();
foreach ($AVC->state_fieldNames as $fieldValue) {
	$AVC_search_options[] = '"'.$fieldValue.'"';
}


$AVC_search_options_string = implode(",", $AVC_search_options);

$AVC_having_value = "";
if(!empty($AVC->state_having["search"])){
	$AVC_having_split = explode("%", $AVC->state_having["search"]);
	$AVC_having_value = $AVC_having_split[1];
}

echo '<input type="text" id="AVC_SEARCH_INPUT'.$AVC->module_id.'" name="AVC_SEARCH_INPUT'.$AVC->module_id.'" value="'.$AVC_having_value.'" />';

echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_SEARCH'.$AVC->module_id.'($(\'AVC_SEARCH_INPUT'.$AVC->module_id.'\').value);">'.JText::_("Search").'</a>';
echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_SEARCH'.$AVC->module_id.'(\'\');">'.JText::_("Clear").'</a>';

echo '</span>';

$JS_AVC_layout_search = '
///////////////////////////////////////
//
// AVC LAYOUT SEARCH '.$AVC->module_id.'
//
///////////////////////////////////////

var AVC_LAYOUT_FIELDLIST'.$AVC->module_id.' = ['.$AVC_search_options_string.'];

function AVC_LAYOUT_SEARCH'.$AVC->module_id.'(searchValue){

	having = "{}";

	if(searchValue){
		var havingArray = [];
		for (var i=0; i < AVC_LAYOUT_FIELDLIST'.$AVC->module_id.'.length; i++) { 
			havingArray.push(AVC_LAYOUT_FIELDLIST'.$AVC->module_id.'[i]+\' LIKE \\\'%\'+searchValue+\'%\\\'\');
		}
		console.log(havingArray);
		having = \'{"search":"\'+havingArray.join(\' OR \')+\'"}\';
		console.log(having);
	}

	AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'having\', JSON.parse(having));
	AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');

}

';

JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout_search);


