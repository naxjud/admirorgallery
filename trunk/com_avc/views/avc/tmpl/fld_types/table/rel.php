<?php

// No direct access
defined('_JEXEC') or die('Restricted access');

if(!empty($FIELD_VALUE) || $FIELD_VALUE!=0){

///////////////////////////////////////////////
//	CREATE LISTING
///////////////////////////////////////////////

$dbObject = JFactory::getDBO();
$query = $dbObject->getQuery(true);
$query->select( array( $FIELD_PARAMS["select"] ) );
$query->from( $FIELD_PARAMS["from"] );

// WHERE
if(!empty($FIELD_PARAMS["where"])){
    foreach ($FIELD_PARAMS["where"] as $value) {
		if(!is_numeric($FIELD_VALUE)){
     		$value = str_replace("FIELD_VALUE", $dbObject->Quote($FIELD_VALUE), $value);
		}else{
     		$value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
		}
        $query->where($value);
    }
}

// HAVING
if(!empty($FIELD_PARAMS["having"])){
    foreach ($FIELD_PARAMS["having"] as $value) {    	
		if(!is_numeric($FIELD_VALUE)){
     		$value = str_replace("FIELD_VALUE", $dbObject->Quote($FIELD_VALUE), $value);
		}else{
     		$value = str_replace("FIELD_VALUE", $FIELD_VALUE, $value);
		}
        $query->having($value);
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
$ROW = $dbObject->loadAssocList();

echo $FIELD_VALUE." - ";
echo "<i>".implode(", ", $ROW[0])."</i>";
		
}

if(empty($AVC_RELS)){
    $AVC_RELS = array();
}
if(empty($AVC_RELS[ $ROW_ID ])){
    $AVC_RELS[ $ROW_ID ] = array();
}
if(empty($AVC_RELS[ $ROW_ID ][ $FIELD_ALIAS ])){
    $AVC_RELS[ $ROW_ID ][ $FIELD_ALIAS ] = array();
}
$AVC_RELS[ $ROW_ID ][ $FIELD_ALIAS ]["key"] = $FIELD_VALUE;
$AVC_RELS[ $ROW_ID ][ $FIELD_ALIAS ]["value"] = implode(", ", $ROW[0]);

