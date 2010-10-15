<?php

if(!empty($_POST["ag_itemURL"]) && is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_itemURL = $_POST["ag_itemURL"];
     $file = JRequest::getVar( 'file_upload', null, 'files' );

     $config =& JFactory::getConfig();
     $tmp_dest = $config->getValue('config.tmp_path');

     $ag_ext_valid = array ("jpg","jpeg","gif","png","zip");

     if(isset($file) && !empty($file['name'])){ 
	  //Clean up filename to get rid of strange characters like spaces etc
	  $filename = JFile::makeSafe($file['name']);
	  $ag_file_ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

	  $src = $file['tmp_name'];
	  $dest = $tmp_dest.'/'.$filename;

	  // FILTER EXTENSION
	  $ag_ext_check = array_search($ag_file_ext,$ag_ext_valid);
	  if(is_numeric($ag_ext_check)){
	       if ( JFile::upload($src, $dest) ) {
		    if ($ag_file_ext == "zip") {
			 if(JArchive::extract($tmp_dest.'/'.$filename, JPATH_SITE.$ag_itemURL)){
			      JFile::delete($tmp_dest.'/'.$filename);
			      $ag_notice[] = Array ('ZIP package is uploaded and extracted:',$filename);
			 }
		    }else{
			 if(JFile::copy($tmp_dest.'/'.$filename, JPATH_SITE.$ag_itemURL.$filename)){
			      JFile::delete($tmp_dest.'/'.$filename);
			      $ag_notice[] = Array ('Image is uploaded:',$filename);
			 }
		    }
	       } else {
		    $ag_error[] = Array ('Cannot upload file:',$filename);
	       }
	  }else{
	       $ag_error[] = Array ("Only jpg, jpeg, gif, png and zip are valid extensions.");
	  }

     }else{
	  $ag_error[] = Array ('No file set.');
     }

}else{
     $ag_error[] = Array ("No folder selected.");
}

?>
