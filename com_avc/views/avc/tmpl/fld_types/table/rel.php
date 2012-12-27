<?php

///////////////////////////////////////////////
//	CREATE LISTING
///////////////////////////////////////////////

$dbObject = JFactory::getDBO();
$query = $dbObject->getQuery(true);
$query->select('*');
$query->from($dbObject->nameQuote($FIELD_REL->table));
$query->where($dbObject->nameQuote($FIELD_REL->key)."=".$FIELD_VALUE);
$dbObject->setQuery($query);
$ROW = $dbObject->loadAssocList();
echo $ROW[0][$FIELD_PARAMS->label] . " (" . $FIELD_VALUE . ")";

