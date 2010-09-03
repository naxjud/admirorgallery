<?php

if(!empty($_POST["ag_itemURL"]) && is_dir(JPATH_SITE.$_POST["ag_itemURL"])){

     $ag_item_php = JPATH_SITE.$_POST["ag_itemURL"];
     $ag_root_php = JPATH_SITE.'/';
     $setChangeNameTo=$_POST["setChangeNameTo"];

     if(empty($setChangeNameTo)){
	  $setChangeNameTo = "new-folder-".date(c);
     }

     if(file_exists($ag_item_php."/".$setChangeNameTo)){
	  $setChangeNameTo = $setChangeNameTo."-".date(c);
     }

     // CREATE WEBSAFE TITLES
     foreach($webSafe as $key => $value){
	  $setChangeNameTo = str_replace($value,"-",$setChangeNameTo);
     }	
     $setChangeNameTo = htmlspecialchars(strip_tags($setChangeNameTo));

     if(JFolder::create($ag_item_php."/".$setChangeNameTo,0755)){
	  $ag_notice[] = Array ("Folder created:",$setChangeNameTo);
     }else{
	  $ag_error[] = Array ("Cannot create folder.",$setChangeNameTo);
     }

}else{
     $ag_error[] = Array ("No parent folder selected.");
}

?>