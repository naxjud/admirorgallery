<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/jquery.easing-1.3.pack.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/sexylightbox.v2.3.jquery.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/sexylightbox.css');
$rel = 'sexylightbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= '';
$doc->addScriptDeclaration('
       jQuery(document).ready(function(){
      SexyLightbox.initialize({color:\'white\',dir:\''.$joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'\'});});');
$initCode='SexyLightbox.initialize({color:\'white\',dir:\''.$joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'\'});';	  
?>