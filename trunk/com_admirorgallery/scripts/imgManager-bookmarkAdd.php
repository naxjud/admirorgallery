<?php

if(!empty($_POST["ag_itemURL"]) && is_dir(JPATH_SITE.$_POST["ag_itemURL"])){
     $ag_itemURL = $_POST["ag_itemURL"];
     $ag_bookmarkFile=JPATH_SITE.'/administrator/components/com_admirorgallery/assets/bookmarks.xml';

     $bookmarkCheck= false;
     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $ag_bookmarkFile );
     if(isset($ag_bookmarks_xml->document->bookmark)){
         $ag_bookmarks_array = $ag_bookmarks_xml->document->bookmark;

         // CHECK IS BOOKMARK ALREADY EXISTS
         $bookmarkCheck = false;
         if(!empty($ag_bookmarks_array)){
              foreach($ag_bookmarks_array as $key => $value){
                   if($ag_check_root_php.$value->data() == $ag_itemURL)
                   {
                        $bookmarkCheck = true;
                   }
              }
         }
     }
     if($bookmarkCheck == false){
	  // WRITE NEW BOOKMARK XML
	  $ag_content = "";
	  $ag_content.="<bookmarks>"."\n";
	  if(!empty($ag_bookmarks_array)){
	       foreach($ag_bookmarks_array as $key => $value){
		    if(!empty($value)){
			 $ag_content.='  <bookmark>'.$value->data().'</bookmark>'."\n";
		    }
	       }
	  }
	  $ag_content.='  <bookmark>'.$ag_itemURL.'</bookmark>'."\n";

	  $ag_content.="</bookmarks>"."\n";

	  if(!empty($ag_content)){
	       $handle = fopen($ag_bookmarkFile,"w") or die("");
	       if(fwrite($handle,$ag_content)){
		    $ag_notice[] = Array ("Gallery added:",$ag_itemURL);
	       }else{
		    $ag_error[] = Array ("Cannot write gallery listing:",$ag_itemURL);
	       }
	       fclose($handle);
	  }

     }else{
	  $ag_error[] = Array ("Gallery already exists:",$ag_itemURL);
     }

}else{
     $ag_error[] = Array ("No folder selected.");
}

?>