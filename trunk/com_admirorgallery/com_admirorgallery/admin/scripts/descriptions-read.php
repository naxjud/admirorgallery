<?php

// This script is part of ACMS, Admiror Content Management System.
// Copyright Admiror Design Studio, 2009. All rights reserved.
// Script by Igor Kekeljevic.


$ag_url_php = urldecode($_POST['ag_url_php']);
$ag_url_desc = $_POST['ag_url_desc'];
$ag_lang_available = $_POST['ag_lang_available'];
$ag_lang_availableArray = split(",",$ag_lang_available);

require_once ($ag_url_php.'/administrator/components/com_admirorgallery/scripts/descriptions-lineRender.php');

$ag_content='';

$file=fopen($ag_url_desc,"r");
while (!feof($file)) 
  { 
  $ag_content.=fgetc($file);
  }
fclose($file);



$ag_matchCheck = Array();

// Create default langTag field
$ag_lang_tag="default";
$ag_lang_name="default";
$ag_lang_extract=ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$ag_content);
$ag_matchCheck[]=$ag_lang_name;

// Create site langTag fields
for($a = 0; $a < count($ag_lang_availableArray) ; $a+=2) {// List descriptions for installed languages

     $ag_lang_tag=strtolower($ag_lang_availableArray[$a]);
     $ag_lang_name=$ag_lang_availableArray[$a+1];
     $ag_lang_extract=ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$ag_content);

     if(strlen($ag_lang_extract)>0){
          $ag_matchCheck[]=$ag_lang_tag;
     }
}


// Create other langTag fields
if(count($ag_matchCheck)>0){

     if (preg_match_all("#{(.*?)}#i", $ag_content, $ag_matches, PREG_PATTERN_ORDER) > 0) {
          for ($i = 0; $i < count($ag_matches[0]) ; $i+=2) {
               if(!is_numeric(array_search(strtolower($ag_matches[1][$i]),$ag_matchCheck))){
                         $ag_lang_tag=strtolower($ag_matches[1][$i]);
                         $ag_lang_name=$ag_matches[1][$i];
                         $ag_lang_extract=ag_desc_lineRender($ag_lang_tag,$ag_lang_name,$ag_content);
               }
          }
     }

}





?>
