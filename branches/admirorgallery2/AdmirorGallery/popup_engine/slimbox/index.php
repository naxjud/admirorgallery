<?php
defined('_JEXEC') or die('Restricted access');
$this->loadJS('popup_engine/'.$this->params['popupEngine'].'/slimbox2.js');
$this->loadCSS('popup_engine/'.$this->params['popupEngine'].'/slimbox2.css');
$this->popupEngine->rel = 'lightbox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->cssClass= '';
$this->popupEngine->initCode ='
jQuery("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});';
?>