<?php

$selected_f=NULL;
$selected_t=NULL;
if($field_value=="1")
{
	echo '<a class="icon_16_true button_16" href="#" onclick="tblEdit(\''.$item->id.'\',\''.$field_alias.'\',\'0\');return false;">&nbsp;</a>';
}else{
	echo '<a class="icon_16_false button_16" href="#" onclick="tblEdit(\''.$item->id.'\',\''.$field_alias.'\',\'1\');return false;">&nbsp;</a>';
}