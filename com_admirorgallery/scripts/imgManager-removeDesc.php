<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_url_desc = $_POST["ag_itemURL"];

    // GET EXTENSION 
     $ag_file_ext = substr(strrchr(basename($_POST["ag_itemURL"]),'.'),1);

     $ag_url_desc = substr($ag_url_desc,0,strlen($ag_url_desc)-strlen($ag_file_ext));
     $ag_url_desc = JPATH_SITE.$ag_url_desc."desc";

     if(file_exists($ag_url_desc)){
	  if(JFile::delete($ag_url_desc)){
	       $ag_notice[] = Array ("Description removed:",basename($ag_url_desc));
	  }else{
	       $ag_error[] = Array ("Cannot remove desciption:",basename($ag_url_desc));
	  }
     }else{
	  $ag_error[] = Array ("No description founded:",basename($_POST["ag_itemURL"]));
     }

}else{
     $ag_error[] = Array ("No image selected.");
}

?>