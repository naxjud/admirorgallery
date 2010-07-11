<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/pirobox.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/css/style.css');
$popup->rel = 'pirobox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->cssClass= 'pirobox_AdmirorGallery'.$galleryCount.''.$articleID;
include_once(JPATH_BASE.DS.'plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/include.php');
?>
