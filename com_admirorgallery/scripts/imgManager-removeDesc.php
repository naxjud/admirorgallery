<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

    $ag_itemURL = $_POST["ag_itemURL"];

    $ag_itemURL_desc = dirname($ag_itemURL).'/'.JFile::stripExt(basename($ag_itemURL)).'.desc';

     if(file_exists(JPATH_SITE.$ag_itemURL_desc)){
	  if(JFile::delete(JPATH_SITE.$ag_itemURL_desc)){
	       $ag_notice[] = Array ("Description removed:",basename($ag_itemURL));
	  }else{
	       $ag_error[] = Array ("Cannot remove desciption:",basename($ag_itemURL));
	  }
     }else{
	  $ag_error[] = Array ("No description founded:",basename($ag_itemURL));
     }

}else{
     $ag_error[] = Array ("No image selected.");
}

?>