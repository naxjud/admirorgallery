<?php

$ag_preview_checked_array = $_POST['ag_preview_PRIORITY'];

if(!empty($ag_preview_checked_array)){
     foreach($ag_preview_checked_array as $key => $value){

	  $ag_itemURL = $key;
	  $ag_priority = $value;
	  $ag_folderName = dirname($ag_itemURL);

	  if(is_numeric($ag_priority)){

	      // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
	      $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
	      $ag_imgXML_path=$ag_pathWithStripExt.".xml";
	      if(JFIle::exists($ag_pathWithStripExt.".XML")){
		  $ag_imgXML_path=$ag_pathWithStripExt.".XML";
	      }

	      $ag_priority_new = '<priority>'.$ag_priority.'</priority>';

	      $ag_imgXML_priority="";
	      if(file_exists($ag_imgXML_path)){
		$ag_imgXML_xml = & JFactory::getXMLParser( 'simple' );
		$ag_imgXML_xml->loadFile($ag_imgXML_path);
		$ag_imgXML_priority =& $ag_imgXML_xml->document->priority[0]->data();
	      }

	  if($ag_imgXML_priority != $ag_priority){
	      if(file_exists($ag_imgXML_path)){
		  $file=fopen($ag_imgXML_path,"r");
		  while (!feof($file))
		  {
			$ag_imgXML_content.=fgetc($file);
		  }
		  fclose($file);
		  $ag_imgXML_content = preg_replace("#<priority[^}]*>(.*?)</priority>#s", $ag_priority_new,$ag_imgXML_content);
	      }else{
		  $timeStamp = date ("YmdHs", filemtime(JPATH_SITE.$ag_itemURL));
		  $ag_imgXML_content = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<image>'."\n".'<date>'.$timeStamp.'</date>'."\n".$ag_priority_new."\n".'<captions>'."\n".'</captions>'."\n".'</image>';
	      }
//  	      echo htmlentities($ag_itemURL, ENT_QUOTES);
//  	      echo htmlentities($ag_imgXML_path, ENT_QUOTES);
//  	      echo htmlentities($ag_imgXML_content, ENT_QUOTES);

	      if(!empty($ag_imgXML_content)){
		  $handle = fopen($ag_imgXML_path,"w") or die("");
		  if(fwrite($handle,$ag_imgXML_content)){
			$ag_notice[] = Array ("Description file created:", basename($ag_itemURL));
		  }else{
			$ag_error[] = Array ("Cannot write description file:", basename($ag_itemURL));
		  }
		  fclose($handle);
	      }
	    }

	  }else{
	      if(!empty($ag_priority)){
		  $ag_error[] = Array ("Priority must be numeric value for image:", $ag_itemURL);
	      }
	  }
     }
}else{
     $ag_error[] = Array ("No item checked.");
}


?>