<?php
defined('_JEXEC') or die('Restricted access');

$this->loadJS('popups/'.$this->params['popupEngine'].'/js/slimbox2.js');
$this->loadCSS('popups/'.$this->params['popupEngine'].'/css/slimbox2.css');
$this->popupEngine->rel = 'lightbox[AdmirorGallery'.$this->getGalleryID().']';
?>