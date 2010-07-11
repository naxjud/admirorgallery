<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/shadowbox.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/shadowbox.css');
$popup->rel = 'shadowbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->cssClass= '';
$ag->addJavaScriptCode('Shadowbox.init({
    modal: true,
	counterType: "default",
	slideshowDelay : 0
});');
?>