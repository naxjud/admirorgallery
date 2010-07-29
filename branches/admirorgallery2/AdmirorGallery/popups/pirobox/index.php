<?php
defined('_JEXEC') or die('Restricted access');

$this->loadJS('popups/'.$this->params['popupEngine'].'/js/pirobox.js');
$this->loadJS('popups/'.$this->params['popupEngine'].'/js/piroboxInit.js');
$this->loadCSS('popups/'.$this->params['popupEngine'].'/css/style.css');
$this->popupEngine->rel = 'pirobox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->cssClass= 'pirobox_AdmirorGallery'.$this->getGalleryID();
?>
