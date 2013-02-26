<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

echo '<span class="AVC_LAYOUT_EXT">';

foreach ($AVC->output[0] as $fieldAlias => $fieldValue) {

	$AVC_search_options[] = $fieldAlias;

}

$AVC_having_split = explode(" LIKE ", $AVC->state_having);
$AVC_having_alias = $AVC_having_split[0];
$AVC_having_split2 = explode("%", $AVC_having_split[1]);
$AVC_having_value = $AVC_having_split2[1];

echo '<select id="AVC_SEARCH_SELECT'.$AVC->module_id.'" name="AVC_SEARCH_SELECT'.$AVC->module_id.'">';
foreach ($AVC_search_options as $option) {
	$selected = "";
	if($option == $AVC_having_alias){
		$selected = "selected";
	}
	echo '<option value="'.$option.'" '.$selected.'>'.JText::_($option).'</option>';
}

echo '</select>';

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


function AVC_LAYOUT_SEARCH'.$AVC->module_id.'(searchValue){

	if(searchValue){
		searchValue = $(\'AVC_SEARCH_SELECT'.$AVC->module_id.'\').value+" LIKE \'%"+searchValue+"%\'";
	}

	AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'having\', searchValue);

	AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');

}

';

JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout_search);


