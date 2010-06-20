<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/slimbox2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/slimbox2.css');
$rel = 'lightbox[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= '';
?>