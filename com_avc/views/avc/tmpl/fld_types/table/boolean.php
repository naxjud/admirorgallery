<?php

$selected_f=NULL;
$selected_t=NULL;

// JAVASCRIPT ONCLICK FUNCION
$field_type_boolean_script='
adminlist_deselect_all();
adminlist_select(\'' . $ROW_ID . '\');
var newInput = document.createElement(\'input\');
$(this).getParent(\'td\').appendChild(newInput)
						 .set(\'name\',\'' . $FIELD_ALIAS . '\')
						 .set(\'type\',\'hidden\')
						 .set(\'value\',myBoolean);
$(\'task\').value = \'save\';
$(\'adminForm\').submit();
return false;
';

if($FIELD_VALUE=="1")
{
	echo '<a class="icon_16_true button_16" href="#" onclick="var myBoolean=0;' . $field_type_boolean_script . '">&nbsp;</a>';
}else{
	echo '<a class="icon_16_false button_16" href="#" onclick="var myBoolean=1;' . $field_type_boolean_script . '">&nbsp;</a>';
}

