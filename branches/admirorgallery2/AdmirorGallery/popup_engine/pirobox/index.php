<?php
defined('_JEXEC') or die('Restricted access');
$AG->loadJS('popup_engine/'.$AG->params['popupEngine'].'/pirobox.js');
$AG->loadCSS('popup_engine/'.$AG->params['popupEngine'].'/css/style.css');
$AG->popupEngine->rel = 'pirobox[AdmirorGallery'.$AG->getGalleryID().']';
$AG->popupEngine->cssClass= 'pirobox_AdmirorGallery'.$AG->getGalleryID();
include_once(JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$AG->params['popupEngine'].'/include.php');
?>
