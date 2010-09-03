<?php

$ag_preview_checked_array = $_POST['ag_preview_CBOX'];

if(!empty($ag_preview_checked_array)){
     foreach($ag_preview_checked_array as $key => $value){
	  // DELETE
	  if(is_dir(JPATH_SITE.'/'.$value)){
	       if(JFolder::delete(JPATH_SITE.'/'.$value)){
		    $ag_notice[] = Array ("Item deleted:",$value);
	       }else{
		    $ag_error[] = Array ("Cannot delete item:",$value);
	       }
	  }else{
	       if(JFile::delete(JPATH_SITE.'/'.$value)){
		    // REMOVE DESCRIPTION 
		    $ag_file_ext = substr(strrchr(basename($value),'.'),1);
		    $ag_url_desc = $value;
		    $ag_url_desc = substr($ag_url_desc,0,strlen($ag_url_desc)-strlen($ag_file_ext));
		    $ag_url_desc = JPATH_SITE.$ag_url_desc."desc";
                    if (file_exists($ag_url_desc)){
                        JFile::delete($ag_url_desc);
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