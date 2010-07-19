<?php

// This script is part of ACMS, Admiror Content Management System.
// Copyright Admiror Design Studio, 2009. All rights reserved.
// Script by Igor Kekeljevic.

$ag_url_php = urldecode($_POST['ag_url_php']);
$ag_lang_available = $_POST['ag_lang_available'];
$ag_lang_availableArray = split(",",$ag_lang_available);

require_once ($ag_url_php.'/administrator/components/com_admirorgallery/scripts/descriptions-lineRender.php');

// Create default langTag field
$ag_lang_tag="default";
$ag_lang_name="default";
$content='';
$ag_lang_extract=ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$content);


// Create site langTag fields
for($a = 0; $a < count($ag_lang_availableArray) ; $a+=2) {// List descriptions for installed languages

     $ag_lang_tag=strtolower($ag_lang_availableArray[$a]);
     $ag_lang_name=$ag_lang_availableArray[$a+1];
     $content='';
     $ag_lang_extract=ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$content);

}


?>
