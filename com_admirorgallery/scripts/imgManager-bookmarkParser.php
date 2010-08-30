<?php
//require_once ( JPATH_BASE .DS.'includes'.DS.'defines.php' );
//require_once ( JPATH_BASE .DS.'includes'.DS.'framework.php' );
//$mainframe =& JFactory::getApplication('site');
//$mainframe->initialise();

function imgManager_bookmarkParser($bookmarkPath){

     $ag_bookmarks = "";

     // RENDER BOOKMARKS
     $ag_bookmarks_xml =& JFactory::getXMLParser( 'simple' );
     $ag_bookmarks_xml->loadFile( $bookmarkPath );
     foreach($ag_bookmarks_xml->document->bookmark as $key => $value){
	  $ag_bookmarks.=$ag_bookmarks_xml->document->bookmark[$key]->data()."[split]";
     }
     if(!empty($ag_bookmarks)){
	  return substr($ag_bookmarks,0,-7);
     }

}

?>