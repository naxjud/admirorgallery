<?php


// $ag_preview_checked_array = $_POST['ag_preview_CBOX'];


if(isset($_POST["ag_itemURL"])){
     if(isset($_POST["setChangeNameTo"])){

	  $ag_itemURL = strtolower($_POST["ag_itemURL"]);
	  $setChangeNameTo = strtolower($_POST["setChangeNameTo"]);

	  // CREATE WEBSAFE TITLES
	  foreach($webSafe as $key => $value){
	       $setChangeNameTo = str_replace($value,"-",$setChangeNameTo);
	  }	
	  $setChangeNameTo = htmlspecialchars(strip_tags($setChangeNameTo));

	  if(!is_dir(JPATH_SITE.'/'.$ag_itemURL)){
	       $ag_file_ext = JFile::getExt(basename($ag_itemURL));
	       $ag_itemURL_desc = JPATH_SITE.'/'.$ag_itemURL;
	       $ag_itemURL_desc = substr($ag_itemURL_desc,0,strlen($ag_itemURL_desc)-strlen($ag_file_ext));
	       $ag_itemURL_desc.="desc";
               $ag_file_new_name =JPATH_SITE.'/'.dirname($ag_itemURL).'/'.$setChangeNameTo.'.'.$ag_file_ext;
               if (!file_exists($ag_file_new_name)){
                   if(rename(JPATH_SITE.'/'.$ag_itemURL,$ag_file_new_name)){
                        if(file_exists($ag_itemURL_desc)){
                             rename($ag_itemURL_desc,JPATH_SITE.'/'.dirname($ag_itemURL).'/'.$setChangeNameTo.'.desc');
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
               if (!file_exists(JPATH_SITE.'/'.dirname($ag_itemURL).'/'.$setChangeNameTo)){
                   if(rename(JPATH_SITE.'/'.$ag_itemURL,JPATH_SITE.'/'.dirname($ag_itemURL).'/'.$setChangeNameTo)){
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