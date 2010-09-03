<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_url_desc = $_POST["ag_itemURL"];
     $ag_url_desc = substr($ag_url_desc,0,strlen($ag_url_desc)-strlen(JFile::getExt($_POST["ag_itemURL"])));
     $ag_url_desc = JPATH_SITE.$ag_url_desc."desc";
     $ag_desc_content = $_POST["ag_desc_content"];
     $ag_desc_tags = $_POST["ag_desc_tags"];

     $ag_content = " ";

     if(!empty($ag_desc_content)){
	  foreach($ag_desc_content as $key => $value) {
	       if(!empty($value)){
		    $ag_content .= '{'.$ag_desc_tags[$key].'}'.stripslashes(htmlspecialchars($value)).'{/'.$ag_desc_tags[$key].'}'."\n";
	       }	  
	  }
     }

     if(!empty($ag_content)){
	  $handle = fopen($ag_url_desc,"w") or die("");
	  if(fwrite($handle,$ag_content)){
	       $ag_notice[] = Array ("Description file created:", basename($ag_url_desc));
	  }else{
	       $ag_error[] = Array ("Cannot write description file:", basename($ag_url_desc));
	  }
	  fclose($handle);
     }

}else{
     $ag_error[] = Array ("No image selected.");
}

?>