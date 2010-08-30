<?php

function ag_sureRemoveDir($dir, $DeleteMe) {
if(!is_dir($dir)){unlink($dir); return;}
     if(!$dh = @opendir($dir)) return;
     while (false !== ($obj = readdir($dh))) {
	  if($obj=='.' || $obj=='..') continue;
	  if (!@unlink($dir.'/'.$obj)) ag_sureRemoveDir($dir.'/'.$obj, true);
     }

     closedir($dh);
     if ($DeleteMe){
	  @rmdir($dir);
     }
}

$case = $_POST["ag_desc_action"];
switch ($case){
    case "description":{
        $ag_desc_content=stripslashes(htmlspecialchars($_POST["ag_desc_content"]));
        $ag_url_desc=$_POST["ag_url_desc"];

        $ag_desc_contentArray = explode("[split]",$ag_desc_content);

        $ag_content = " ";

        if(!empty($ag_desc_contentArray)){
          for($a = 0; $a < count($ag_desc_contentArray)-1 ; $a+=2) {
              if(strlen($ag_desc_contentArray[$a+1]) > 0){
                    $ag_content .= '{'.substr($ag_desc_contentArray[$a],3,strlen($ag_desc_contentArray[$a])).'}'.$ag_desc_contentArray[$a+1].'{/'.substr($ag_desc_contentArray[$a],3,strlen($ag_desc_contentArray[$a])).'}'."\n";
              }
          }
        }

        if(!empty($ag_content)){
          $handle = fopen($ag_url_desc,"w") or die("");
          if(fwrite($handle,$ag_content)){
                  echo "created";
          };
          fclose($handle);
        }
        break;
    }
    case "removeDesc" :{
        $ag_url_desc = $_POST["ag_url_desc"];
        if(file_exists($ag_url_desc)){
                if(unlink($ag_url_desc)){
                    echo "removed";
                };
        }else{
            echo "noDesc";
        }
        break;
    }
    case "bookmarkAdd" :{
	  if(!empty($_POST["ag_item_php"]) && is_dir($_POST["ag_item_php"])){
	       $ag_item_php = $_POST["ag_item_php"];
	       $ag_root_php = $_POST["ag_root_php"];
	       $ag_bookmarks = $_POST["ag_bookmarks"];
	       $ag_bookmarks_new = "";

	       // PARSE BOOKMARK TO ARRAY
	       $ag_bookmarks_array = explode("[split]",$ag_bookmarks);

	       // CHECK IS BOOKMARK ALREADY EXISTS
	       $bookmarkCheck = false;
	       if(!empty($ag_bookmarks_array)){
		    foreach($ag_bookmarks_array as $key => $value){

			 if($ag_root_php.$value == $ag_item_php)
			 {
			      $bookmarkCheck = true;
			 }
		    }
	       }

	       if($bookmarkCheck == false){
		    // WRITE NEW BOOKMARK XML
		    $ag_content = "";
		    $ag_content.="<bookmarks>"."\n";
		    if(!empty($ag_bookmarks_array)){
			 $ag_root_php_strLen = strlen($ag_root_php);
			 foreach($ag_bookmarks_array as $key => $value){
			      if(!empty($value)){
				   $ag_content.='  <bookmark>'.$value.'</bookmark>'."\n";
				   $ag_bookmarks_new .= $value."[split]";
			      }
			 }
		    }
		    $ag_content.='  <bookmark>'.substr($ag_item_php,$ag_root_php_strLen,strlen($ag_item_php)).'</bookmark>'."\n";
		    $ag_bookmarks_new .= substr($ag_item_php,$ag_root_php_strLen,strlen($ag_item_php))."[split]";

		    $ag_content.="</bookmarks>"."\n";
		    $ag_bookmarks_new = substr($ag_bookmarks_new,0,-7);

		    if(!empty($ag_content)){
			 $ag_bookmarkFile=$ag_root_php.'administrator/components/com_admirorgallery/assets/bookmarks.xml';
			 $handle = fopen($ag_bookmarkFile,"w") or die("");
			 if(fwrite($handle,$ag_content)){
			      echo $ag_bookmarks_new;
			 }else{
			      echo "cannotWriteBookmark";
			 }
			 fclose($handle);
		    }

	       }else{
		    echo "bookmarkExists";
	       }

	  }else{
	       echo "noFolder";
	  }
	  break;
    }
    case "bookmarkRemove" :{
	  if(!empty($_POST["ag_item_php"])){
	       $ag_item_php = $_POST["ag_item_php"];
	       $ag_root_php = $_POST["ag_root_php"];
	       $ag_bookmarks = $_POST["ag_bookmarks"];
	       $ag_bookmarks_new = "";

	       // PARSE BOOKMARK TO ARRAY
	       $ag_bookmarks_array = explode("[split]",$ag_bookmarks);

	       // CHECK IS BOOKMARK ALREADY EXISTS
	       $bookmarkCheck = false;
	       if(!empty($ag_bookmarks_array)){
		    foreach($ag_bookmarks_array as $key => $value){

			 if($ag_root_php.$value == $ag_item_php)
			 {
			      $bookmarkCheck = true;
			 }
		    }
	       }

	       if($bookmarkCheck == true){
		    // WRITE NEW BOOKMARK XML
		    $ag_content = "";
		    $ag_content.="<bookmarks>"."\n";
		    if(!empty($ag_bookmarks_array)){
			 $ag_root_php_strLen = strlen($ag_root_php);
			 foreach($ag_bookmarks_array as $key => $value){
			      if((!empty($value)) && ($value!=substr($ag_item_php,$ag_root_php_strLen,strlen($ag_item_php)))){
				   $ag_content.='  <bookmark>'.$value.'</bookmark>'."\n";
				   $ag_bookmarks_new .= $value."[split]";
			      }
			 }
		    }

		    $ag_content.="</bookmarks>"."\n";
		    $ag_bookmarks_new = substr($ag_bookmarks_new,0,-7);

		    if(!empty($ag_content)){
			 $ag_bookmarkFile=$ag_root_php.'administrator/components/com_admirorgallery/assets/bookmarks.xml';
			 $handle = fopen($ag_bookmarkFile,"w") or die("");
			 if(fwrite($handle,$ag_content)){
			      echo $ag_bookmarks_new;
			 }else{
			      echo "cannotWriteBookmark";
			 }
			 fclose($handle);
		    }

	       }else{
		    echo "bookmarkNotExists";
	       }

	  }else{
	       echo "noFolder";
	  }
	  break;
    }
    case "folderNew" :{
	  if(!empty($_POST["ag_item_php"]) && is_dir($_POST["ag_item_php"])){

	       $ag_item_php = $_POST["ag_item_php"];
	       $ag_root_php = $_POST["ag_root_php"];
	       $setChangeNameTo=$_POST["setChangeNameTo"];

	       if(empty($setChangeNameTo)){
		    $setChangeNameTo = "new-folder-".date(c);
	       }

	       if(file_exists($ag_item_php."/".$setChangeNameTo)){
		    $setChangeNameTo = $setChangeNameTo."-".date(c);
	       }

	       // CREATE WEBSAFE TITLES
	       $webSafe=Array("/"," ",":",".","+");
	       foreach($webSafe as $key => $value){
		    $setChangeNameTo = str_replace($value,"-",$setChangeNameTo);
	       }	
	       $setChangeNameTo = htmlspecialchars(strip_tags($setChangeNameTo));

	       if(mkdir($ag_item_php."/".$setChangeNameTo,0755)){
		    echo $setChangeNameTo;
	       }else{
		    echo "folderNotCreated";
	       }

	  }else{
	       echo "noFolder";
	  }
        break;
    }
    case "upload" :{
	  echo 'Upload';
        break;
    }
    case "rename" :{
	  echo 'Rename';
        break;
    }
    case "remove" :{

	 $ag_preview_checked = $_POST['ag_preview_checked'];

	  if(!empty($ag_preview_checked)){

	       // PARSE CHECKED TO ARRAY
	       $ag_preview_checked_array = explode("[split]",$ag_preview_checked);

	       if(!empty($ag_preview_checked_array)){
		    foreach($ag_preview_checked_array as $key => $value){
			 // DELETE
			 ag_sureRemoveDir($value, true);
		    }
	       }

	  }else{
	       echo "nothingSelected";
	  }

        break;
    }
}



?>
