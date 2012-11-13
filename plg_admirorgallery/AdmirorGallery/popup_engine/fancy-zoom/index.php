<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/fancyzoom.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/fancyzoom.css');
$popup->rel = 'fancyzoom[AdmirorGallery'.$galleryCount.']'.$articleID;
$popup->cssClass= '';
$ag->addJavaScriptCode('jQuery(document).ready(function() {
    jQuery("a[rel='.$popup->rel.']").fancyZoom({scaleImg: true, closeOnClick: true});
	jQuery(\'#zoom_close\').html(\'<img src="plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/images/closebox.png" alt="Close" style="border:none; margin:0; padding:0;" /> \');
});')
?>