<?php

$ag_preview_checked_array = $_POST['ag_preview_CBOX'];

if(!empty($ag_preview_checked_array)){
     foreach($ag_preview_checked_array as $key => $value){

	  $ag_itemURL = $value;
	  $ag_folderName = dirname($ag_itemURL);

	  // DELETE
	  if(is_dir(JPATH_SITE.'/'.$value)){
	       if(JFolder::delete(JPATH_SITE.'/'.$value)){
		    $ag_notice[] = Array ("Item deleted:",$value);
	       }else{
		    $ag_error[] = Array ("Cannot delete item:",$value);
	       }
	  }else{
	       if(JFile::delete(JPATH_SITE.'/'.$value)){

		    // Set Possible Description File Apsolute Path // Instant patch for upper and lower case...
		    $ag_pathWithStripExt=JPATH_SITE.$ag_folderName.'/'.JFile::stripExt(basename($ag_itemURL));
		    $ag_imgXML_path=$ag_pathWithStripExt.".XML";
		    if(JFIle::exists($ag_pathWithStripExt.".xml")){
			$ag_imgXML_path=$ag_pathWithStripExt.".xml";
		    }

                    if (file_exists($ag_imgXML_path)){
                        JFile::delete($ag_imgXML_path);
                    }

		    $ag_notice[] = Array ("Item deleted:",$value);
	       }else{
		    $ag_error[] = Array ("Cannot delete item:",$value);
	       }
	  }
     }
}else{
     $ag_error[] = Array ("No item checked.");
}

?>