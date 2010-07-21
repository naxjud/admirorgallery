<?php

$file = JRequest::getVar( 'file_upload', null, 'files' );

$config =& JFactory::getConfig();
$tmp_dest = $config->getValue('config.tmp_path');

$file_type = "application/zip";

if(isset($file) && !empty($file['name'])){ 
      //Clean up filename to get rid of strange characters like spaces etc
      $filename = JFile::makeSafe($file['name']);

      $src = $file['tmp_name'];
      $dest = $tmp_dest.'/'.$filename;

      //First check if the file has the right extension
      if ($file['type'] == $file_type || $file_type == '*') { 
         if ( JFile::upload($src, $dest) ) {
			if(JArchive::extract($tmp_dest.'/'.$filename, JPATH_SITE.'/plugins/content/AdmirorGallery/templates' )){
			     JFile::delete($tmp_dest.'/'.$filename);
			}
			$msg = JText::_('Template is installed.');
         } else {
              $msg = '<span class="ag_errorMessage">'.JText::_('Cannot upload file to temp folder. Please check permissions.').'</span>';
         }
      } else {
         $msg = '<span class="ag_errorMessage">'.JText::_('Only zip arhives can be installed.').'</span>';
      }
	echo "<script type='text/javascript'>ag_showMessage('". $msg ."');</script>";
}

?>
