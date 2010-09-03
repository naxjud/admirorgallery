<?php

if(!empty($_POST["ag_itemURL"]) && is_dir(JPATH_SITE.$_POST["ag_itemURL"])){
     $ag_item_php = JPATH_SITE.$_POST["ag_itemURL"];
     $ag_root_php = JPATH_SITE.'/';

     $ag_bookmarkFile=$ag_root_php.'administrator/components/com_admirorgallery/assets/bookmarks.xml';

     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $ag_bookmarkFile );
     $ag_bookmarks_array = $ag_bookmarks_xml->document->bookmark;

     // CHECK IS BOOKMARK ALREADY EXISTS
     $bookmarkCheck = false;
     if(!empty($ag_bookmarks_array)){
	  foreach($ag_bookmarks_array as $key => $value){
	       if($ag_root_php.$value->data() == $ag_item_php)
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
			 $ag_content.='  <bookmark>'.$value->data().'</bookmark>'."\n";
		    }
	       }
	  }
	  $ag_content.='  <bookmark>/'.substr($ag_item_php,$ag_root_php_strLen,strlen($ag_item_php)).'</bookmark>'."\n";

	  $ag_content.="</bookmarks>"."\n";

	  if(!empty($ag_content)){
	       $handle = fopen($ag_bookmarkFile,"w") or die("");
	       if(fwrite($handle,$ag_content)){
		    $ag_notice[] = Array ("Gallery added:",basename($ag_item_php));
	       }else{
		    $ag_error[] = Array ("Cannot write gallery listing:",basename($ag_item_php));
	       }
	       fclose($handle);
	  }

     }else{
	  $ag_error[] = Array ("Gallery already exists:",basename($ag_item_php));
     }

}else{
     $ag_error[] = Array ("No folder selected.");
}

?>