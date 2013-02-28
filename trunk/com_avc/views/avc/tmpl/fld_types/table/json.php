<?php

$FIELD_VALUE_JSON = json_decode($FIELD_VALUE, true);

if(!empty($FIELD_VALUE_JSON)){
$final_output = array();
foreach ($FIELD_VALUE_JSON as $key => $value) {
	$final_output[] = strtoupper($key).": ".$value;
}
echo implode(", ", $final_output);

}
