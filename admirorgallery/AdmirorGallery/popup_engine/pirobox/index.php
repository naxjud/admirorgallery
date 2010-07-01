<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/pirobox.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/css/style.css');
$rel = 'pirobox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= 'pirobox_AdmirorGallery'.$galleryCount.''.$articleID;
include_once(JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/include.php');
?>
