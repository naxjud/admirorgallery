<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'js/jquery.nyroModal-1.6.2.pack.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'styles/nyroModal.css');
$this->popupEngine->className = 'nyroModal';
$this->popupEngine->rel = 'AdmirorGallery'.$this->getGalleryID();
?>