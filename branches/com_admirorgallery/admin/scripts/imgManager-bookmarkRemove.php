<?php

if(isset($_POST["ag_itemURL"])){

     $ag_itemURL = $_POST["ag_itemURL"];

     $ag_bookmarkFile=JPATH_SITE.'/administrator/components/com_admirorgallery/assets/bookmarks.xml';

     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $ag_bookmarkFile );
     $ag_bookmarks_array = $ag_bookmarks_xml->document->bookmark;

     // CHECK IF BOOKMARK ALREADY EXISTS
     $bookmarkCheck = false;
     if(!empty($ag_bookmarks_array)){
	  foreach($ag_bookmarks_array as $key => $value){
	       if($ag_root_php.$value->data() == $ag_itemURL)
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
	       foreach($ag_bookmarks_array as $key => $value){
		    if((!empty($value)) && ($value->data()!=$ag_itemURL)){
			 $ag_content.='  <bookmark>'.$value->data().'</bookmark>'."\n";
		    }
	       }
	  }

	  $ag_content.="</bookmarks>"."\n";

	  if(!empty($ag_content)){
	       $handle = fopen($ag_bookmarkFile,"w") or die("");
	       if(fwrite($handle,$ag_content)){
		    $ag_notice[] = Array ("Gallery removed from listing:",$ag_itemURL);
	       }else{
		    $ag_error[] = Array ("Cannot write gallery listing:",$ag_itemURL);
	       }
	       fclose($handle);
	  }

     }else{
	  $ag_error[] = Array ("Gallery not exists in listing:",$ag_itemURL);
     }

}else{
     $ag_error[] = Array ("No folder selected.");
}


?>