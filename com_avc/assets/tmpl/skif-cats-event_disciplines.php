<?php

defined('_JEXEC') or die('Restricted access');

// echo '<h2>ROWs</h2>';
// echo '<pre>';
// var_dump($ROWs);
// echo '</pre>';

$ADDED_SECTIONS = array();

$DATAs = array();
if(!empty($ROWs)){
foreach ($ROWs as $key => $row) {

if (!in_array($row["section_title"], $ADDED_SECTIONS)) {

	$DATAs[ $key ] = array();
	$DATAs[ $key ]["id"] = $row["section_id"];	
	$DATAs[ $key ]["title"] = $row["section_title"];
	$DATAs[ $key ]["alias"] = JFile::makeSafe( strtolower( str_replace(" ", "-", $row["section_title"]) ) );

	$DATAs[ $key ]["parent_id"] = $config["custom_values"]["parent_id"];

	// CONTENT
	$DATAs[$key]["description"] = '';

	$ADDED_SECTIONS[]=$row["section_title"];

}

}
}

?>



