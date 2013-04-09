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

echo '<input type="text" id="AVC_SEARCH_INPUT'.$AVC->module_id.'" name="AVC_SEARCH_INPUT'.$AVC->module_id.'" value="'.$AVC_having_value.'" class="AVC_LAYOUT_INPUT_SEARCH" />';
echo '<input type="hidden" id="AVC_SEARCH_GROUP_'.$AVC->module_id.'" name="AVC_SEARCH_GROUP_'.$AVC->module_id.'" value="'.$AVC->group.'" />';
echo '<input type="hidden" id="AVC_SEARCH_MODULE_ID_'.$AVC->module_id.'" name="AVC_SEARCH_MODULE_ID_'.$AVC->module_id.'" value="'.$AVC->module_id.'" />';

echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_SEARCH( \''.$AVC->module_id.'\', $(\'AVC_SEARCH_INPUT'.$AVC->module_id.'\').value );">'.JText::_("Search").'</a>';
echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_SEARCH( \''.$AVC->module_id.'\',\'\');">'.JText::_("Clear").'</a>';

echo '</span>';

$JS_AVC_layout_search = '
///////////////////////////////////////
//
// AVC LAYOUT SEARCH '.$AVC->module_id.'
//
///////////////////////////////////////

AVC_LAYOUT_FIELDLIST["'.$AVC->module_id.'"] = ['.$AVC_search_options_string.'];

';

JFactory::getDocument()->addScriptDeclaration($JS_AVC_layout_search);


