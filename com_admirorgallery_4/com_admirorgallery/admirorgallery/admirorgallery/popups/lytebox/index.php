<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Load JavaScript files from current popup folder
$this->loadJS($this->currPopupRoot.'js/lytebox.js');

// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'css/lytebox.css');

// Set REL attribute needed for Popup engine
$this->popupEngine->rel='group:'.$this->getGalleryID().'';

// Set CLASS attribute needed for Popup engine
$this->popupEngine->className= 'lytebox_AdmirorGallery'.$this->getGalleryID();

?>