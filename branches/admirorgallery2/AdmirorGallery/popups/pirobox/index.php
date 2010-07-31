<?php
defined('_JEXEC') or die('Restricted access');

$this->loadJS($this->currPopupRoot.'js/pirobox.js');
$this->loadJS($this->currPopupRoot.'js/piroboxInit.js');
$this->loadCSS($this->currPopupRoot.'css/style.css');
$this->popupEngine->rel = 'pirobox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->className= 'pirobox_AdmirorGallery'.$this->getGalleryID();
?>
