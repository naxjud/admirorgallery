<?php
defined('_JEXEC') or die('Restricted access');
$AG->loadJS('popup_engine/'.$AG->params['popupEngine'].'/slimbox2.js');
$AG->loadCSS('popup_engine/'.$AG->params['popupEngine'].'/slimbox2.css');
$AG->popupEngine->rel = 'lightbox[AdmirorGallery'.$AG->getGalleryID().']';
$AG->popupEngine->cssClass= '';
$AG->popupEngine->initCode ='
jQuery("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});';
?>