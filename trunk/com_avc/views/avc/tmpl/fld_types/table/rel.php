<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

if(!empty($FIELD_VALUE) || $FIELD_VALUE!=0){

$REL_QUERY = $this->views[(int)$FIELD_PARAMS["queryId"]]["query"];

$ROW_KEY = "id";
if(!empty($REL_QUERY["key"])){
    $ROW_KEY = $REL_QUERY["key"];
}
            
// FILTER FIELD VALUE
if(!is_numeric($FIELD_VALUE)){
    $value = $ROW_KEY." = '".$FIELD_VALUE."'";
}else{
    $value = $ROW_KEY." = ".$FIELD_VALUE;
}
$REL_QUERY["having"]["current_key"] = $value;

$ROWS = AvcModelAvc::execQuery($REL_QUERY);

$REL_FIELDS_CONFIG = $this->views[(int)$FIELD_PARAMS["queryId"]]["fields_config"];
$REL_VALUE = array();
if(!empty($REL_FIELDS_CONFIG)){
foreach ($REL_FIELDS_CONFIG as $REL_FIELD_KEY => $REL_FIELD_PARAMS) {
	$REL_VALUE[] = $ROWS[0][$REL_FIELD_KEY];
}
}
echo "<i>".implode(", ", $REL_VALUE)."</i>";
		
}
