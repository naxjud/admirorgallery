<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/js/jquery.nyroModal-1.6.2.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/styles/nyroModal.css');
$popup->cssClass = 'nyroModal';
$popup->rel = 'gal'.$galleryCount.'AdmirorGallery'.$galleryCount.''.$articleID;
?>