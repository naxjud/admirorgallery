<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.easing-1.3.pack.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/sexylightbox.v2.3.jquery.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/sexylightbox.css');
$popup->rel = 'sexylightbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->cssClass= '';
$ag->addJavaScriptCode('
       jQuery(document).ready(function(){
      SexyLightbox.initialize({color:\'white\',dir:\''.$ag->sitePath.'/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'\'});});');
$popup->initCode='SexyLightbox.initialize({color:\'white\',dir:\''.$ag->sitePath.'/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'\'});';
?>