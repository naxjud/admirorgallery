<?php

if(isset($_POST["ag_itemURL"])){
     if(isset($_POST["setChangeNameTo"])){

	  $ag_itemURL = $_POST["ag_itemURL"];
	  $ag_folderName = dirname($ag_itemURL);
	  $setChangeNameTo = $_POST["setChangeNameTo"];

	  // CREATE WEBSAFE TITLES
	  foreach($webSafe as $key => $value){
	       $setChangeNameTo = str_replace($value,"-",$setChangeNameTo);
	  }	
	  $setChangeNameTo = htmlspecialchars(strip_tags($setChangeNameTo));

	  if(!is_dir(JPATH_SITE.$ag_itemURL)){

	      // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
	      $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
	      $ag_imgXML_path=$ag_pathWithStripExt.".XML";
	      if(JFIle::exists($ag_pathWithStripExt.".xml")){
		  $ag_imgXML_path=$ag_pathWithStripExt.".xml";
	      }
	      $ag_file_ext = JFile::getExt(basename($ag_itemURL));

               $ag_file_new_name = dirname($ag_itemURL).'/'.$setChangeNameTo.'.'.$ag_file_ext;
               if (!file_exists(JPATH_SITE.$ag_file_new_name)){
                   if(rename(JPATH_SITE.$ag_itemURL,JPATH_SITE.$ag_file_new_name)){
                        if(file_exists($ag_imgXML_path)){
                             rename($ag_imgXML_path,JPATH_SITE.dirname($ag_itemURL).'/'.$setChangeNameTo.'.xml');
                        }
                        $ag_notice[] = Array ("Image renamed:",basename($ag_itemURL));
                        $_POST["ag_itemURL"] = dirname($ag_itemURL).'/'.$setChangeNameTo.'.'.$ag_file_ext;
                   }else{
                        $ag_error[] = Array ("Cannot rename image:",basename($ag_itemURL));
                   }
               }else{
                    $ag_error[] = Array ("File with the same name already exists.");
               }
	  }else{
               if (!file_exists(JPATH_SITE.dirname($ag_itemURL).'/'.$setChangeNameTo)){
                   if(rename(JPATH_SITE.$ag_itemURL,JPATH_SITE.dirname($ag_itemURL).'/'.$setChangeNameTo)){
                        $ag_notice[] = Array ("Folder renamed:",basename($ag_itemURL));
                        $_POST["ag_itemURL"] = dirname($ag_itemURL).'/'.$setChangeNameTo.'/';
                   }else{
                        $ag_error[] = Array ("Cannot rename Folder:",basename($ag_itemURL));
                   }
                }else{
                    $ag_error[] = Array ("Folder with the same name already exists.");
                }
          }
     }else{
	  $ag_error[] = Array ("New name not set.");
     }
}else{
     $ag_error[] = Array ("No item selected.");
}

?>