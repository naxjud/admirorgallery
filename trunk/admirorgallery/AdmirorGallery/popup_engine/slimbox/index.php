<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/slimbox2.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/slimbox2.css');
$popup->rel = 'lightbox[AdmirorGallery'.$galleryCount.']'.$articleID;
$popup->cssClass= '';
$popup->initCode ='
jQuery("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});';
?>