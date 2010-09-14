<?php

if(!empty($_POST["ag_itemURL"]) && !is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_itemURL = $_POST["ag_itemURL"];
     $ag_itemURL_desc = dirname($ag_itemURL).'/'.JFile::stripExt(basename($ag_itemURL)).".desc";
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
	  $handle = fopen(JPATH_SITE.$ag_itemURL_desc,"w") or die("");
	  if(fwrite($handle,$ag_content)){
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