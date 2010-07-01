<?php
defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/js/jquery.nyroModal-1.6.2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/styles/nyroModal.css');
$rel = 'gal'.$galleryCount.'AdmirorGallery'.$galleryCount.''.$articleID;
$cssClass= 'nyroModal';
$jsInclude='';
?>