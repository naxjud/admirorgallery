<?php

$file = JRequest::getVar( 'file_upload', null, 'files' );
$task = JRequest::getVar( 'task', null, 'post');
$resourceType = substr($task,0,strlen($task)-1);

$config =& JFactory::getConfig();
$tmp_dest = $config->getValue('config.tmp_path');

$file_type = "zip";

if(isset($file) && !empty($file['name'])){ 
      //Clean up filename to get rid of strange characters like spaces etc
      $filename = JFile::makeSafe($file['name']);
      $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

      $src = $file['tmp_name'];
      $dest = $tmp_dest.'/'.$filename;

      //First check if the file has the right extension
      if ($ext == $file_type) {
         if ( JFile::upload($src, $dest) ) {
			if(JArchive::extract($tmp_dest.'/'.$filename, JPATH_SITE.'/plugins/content/AdmirorGallery/'. JFile::makeSafe($task) )){
			     JFile::delete($tmp_dest.'/'.$filename);
			}

		// TEMPLATE DETAILS PARSING
		if(JFIle::exists(JPATH_SITE.'/plugins/content/AdmirorGallery/'.JFile::makeSafe($task).'/'.JFile::stripExt($filename).'/details.xml')){
			$ag_resourceManager_xml =& JFactory::getXMLParser( 'simple' );
			$ag_resourceManager_xml->loadFile(JPATH_SITE.'/plugins/content/AdmirorGallery/'.JFile::makeSafe($task).'/'.JFile::stripExt($filename).'/details.xml');
			if(isset($ag_resourceManager_xml->document->type[0])){
			    $ag_resourceManager_type = $ag_resourceManager_xml->document->type[0]->data();	
			}
		}

		if($ag_resourceManager_type == $resourceType){
		    $ag_notice[] = Array ('ZIP package is installed.',$filename);
		}else{
		    JFolder::delete(JPATH_SITE.'/plugins/content/AdmirorGallery/'.JFile::makeSafe($task).'/'.JFile::stripExt($filename));
		    $ag_notice[] = Array ('ZIP package is not valid resource type.',$filename);
		}


         } else {
              $ag_error[] = Array ('Cannot upload file to temp folder. Please check permissions.');
         }
      } else {
         $ag_error[] = Array ('Only zip archives can be installed.');
      }
}else{
    $ag_error[] = Array ('Archive not selected.');
}

?>
