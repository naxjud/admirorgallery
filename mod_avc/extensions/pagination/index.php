<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

if($AVC->state_limit!=""){

$AVC_pagination_limit = explode(",", $AVC->state_limit);

$AVC_pagination_from = (int)$AVC_pagination_limit[0];
$AVC_pagination_range = (int)$AVC_pagination_limit[1];
$AVC_pagination_total = (int)$AVC->rows_total;
$AVC_pagination_options = array(5,10,15,20,JText::_("All"));

$AVC_pagination_pages = array();
$AVC_pagination_item=0;
$AVC_pagination_page=0;
while ($AVC_pagination_item < $AVC_pagination_total) {
	if(($AVC_pagination_item % $AVC_pagination_range) == 0){
		$AVC_pagination_page++;
		$AVC_pagination_pages[$AVC_pagination_page] = $AVC_pagination_item.",".$AVC_pagination_range;
	}
	$AVC_pagination_item++;
}

$AVC_current_page = floor($AVC_pagination_from/$AVC_pagination_range)+1;


echo '<span class="AVC_LAYOUT_EXT">';

echo '<span class="AVC_LAYOUT_SPAN">'.JText::_("Show").':</span>';

echo '<select class="AVC_LAYOUT_SELECT" onchange="AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'limit\', \''.$AVC_current_page.','.'\'+this.value);AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');">';

if(array_search($AVC_pagination_range, $AVC_pagination_options)===false){
	$AVC_pagination_options[] = $AVC_pagination_range;
}

foreach ($AVC_pagination_options as $option) {
	$value = $option;
	if($option == JText::_("All")){
		$value = 0;
	}
	$selected = "";
	if($option == $AVC_pagination_range){
		$selected = "selected";
	}
	echo '<option value="'.$value.'" '.$selected.'>'.$option.'</option>';
}

echo '</select>';

if($AVC_pagination_range != 0){

	echo '<span class="AVC_LAYOUT_SEPARATOR">&nbsp;</span>';

	if($AVC_pagination_pages[$AVC_current_page-1]){
		echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'limit\', \''.$AVC_pagination_pages[$AVC_current_page-1].'\');AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');">◄</a>';
	}

	echo '<span class="AVC_LAYOUT_SPAN">'.$AVC_current_page.'/'.count($AVC_pagination_pages).'</span>';

	if($AVC_pagination_pages[$AVC_current_page+1]){
		echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'limit\', \''.$AVC_pagination_pages[$AVC_current_page+1].'\');AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');">►</a>';
	}

	echo '<span class="AVC_LAYOUT_SEPARATOR">&nbsp;</span>';

	if(!empty($AVC_pagination_pages)){
		foreach ($AVC_pagination_pages as $key => $value) {
			echo '<a href="#" class="AVC_LAYOUT_BUTTON AVC_LAYOUT_HOVER" onclick="AVC_LAYOUT_HISTORY_UPDATE(\''.$AVC->module_id.'\', \'limit\', \''.$value.'\');AVC_LAYOUT_SUBMIT(\''.$AVC->module_id.'\');">'.$key.'</a>';
		}
	}

}


echo '</span>';

}//if($AVC->state_limit!=""){

