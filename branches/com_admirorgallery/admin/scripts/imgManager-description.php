<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

      $ag_itemURL = $_POST["ag_itemURL"];
      $ag_folderName = dirname($ag_itemURL);

      // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
      $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
      $ag_imgXML_path=$ag_pathWithStripExt.".xml";
      if(JFIle::exists($ag_pathWithStripExt.".XML")){
	  $ag_imgXML_path=$ag_pathWithStripExt.".XML";
      }

     $ag_desc_content = $_POST["ag_desc_content"];
     $ag_desc_tags = $_POST["ag_desc_tags"];

     $ag_captions_new = "";

     $ag_captions_new.="<captions>"."\n";
     if(!empty($ag_desc_content)){
	  foreach($ag_desc_content as $key => $value) {
	       if(!empty($value)){
		    $ag_captions_new .= "\t".'<caption lang="'.strtolower($ag_desc_tags[$key]).'">'.stripslashes(htmlspecialchars($value)).'</caption>'."\n";
	       }	  
	  }
     }
     $ag_captions_new.="</captions>";

    if(file_exists($ag_imgXML_path)){
	  $file=fopen($ag_imgXML_path,"r");
	  while (!feof($file))
	  {
	       $ag_imgXML_content.=fgetc($file);
	  }
	  fclose($file);
	  $ag_imgXML_content = preg_replace("#<captions[^}]*>(.*?)</captions>#s", $ag_captions_new,$ag_imgXML_content);
    }else{
	  $timeStamp = date ("YmdHs", filemtime(JPATH_SITE.$ag_itemURL));
	  $ag_imgXML_content = '<?xml version="1.0" encoding="utf-8"?>'."\n".'<image>'."\n".'<date>'.$timeStamp.'</date>'."\n".'<priority>1</priority>'."\n".$ag_captions_new."\n".'</image>';
    }

// echo htmlentities($ag_imgXML_content, ENT_QUOTES);

     if(!empty($ag_imgXML_content)){
	  $handle = fopen($ag_imgXML_path,"w") or die("");
	  if(fwrite($handle,$ag_imgXML_content)){
	       $ag_notice[] = Array ("Description file created:", basename($ag_itemURL));
	  }else{
	       $ag_error[] = Array ("Cannot write description file:", basename($ag_itemURL));
	  }
	  fclose($handle);
     }

}else{
     $ag_error[] = Array ("No image selected.");
}

?>