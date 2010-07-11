<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.colorbox-min.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/colorbox.css');
$popup->rel = 'colorbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->cssClass= '';
$ag->addJavaScriptCode('
    jQuery(document).ready(function(){
	jQuery("a[rel='.$popup->rel.']").colorbox({scalePhotos: true,maxWidth: 1000, maxHeight:600});
});')
?>