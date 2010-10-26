<?php

if(!empty($_POST["ag_itemURL"]) && is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_itemURL = $_POST["ag_itemURL"];
     $setChangeNameTo=$_POST["setChangeNameTo"];

     if(empty($setChangeNameTo)){
	  $setChangeNameTo = "new-folder-".date("YmdHs");
     }

     if(file_exists(JPATH_SITE.$ag_itemURL.DC.$setChangeNameTo)){
	  $setChangeNameTo = $setChangeNameTo."-".date("YmdHs");
     }

     // CREATE WEBSAFE TITLES
     foreach($webSafe as $key => $value){
	  $setChangeNameTo = str_replace($value,"-",$setChangeNameTo);
     }	
     $setChangeNameTo = htmlspecialchars(strip_tags($setChangeNameTo));

     if(JFolder::create(JPATH_SITE.$ag_itemURL.$setChangeNameTo,0755)){
	  $ag_notice[] = Array ("Folder created:",$setChangeNameTo);
     }else{
	  $ag_error[] = Array ("Cannot create folder.",$setChangeNameTo);
     }

}else{
     $ag_error[] = Array ("No parent folder selected.");
}

?>