<?php
defined('_JEXEC') or die('Restricted access');
$this->popupEngine->js[] = 'popup_engine/'.$this->params['popupEngine'].'/js/slimbox2.js';
$this->popupEngine->css[] = 'popup_engine/'.$this->params['popupEngine'].'/css/slimbox2.css';
$this->popupEngine->rel = 'lightbox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->initCode ='
jQuery("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});';
?>