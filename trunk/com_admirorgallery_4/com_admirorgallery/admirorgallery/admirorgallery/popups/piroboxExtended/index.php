<?php
 /*------------------------------------------------------------------------
# admirorgallery - Admiror Gallery Plugin
# ------------------------------------------------------------------------
# author   Igor Kekeljevic & Nikola Vasiljevski
# copyright Copyright (C) 2011 admiror-design-studio.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.admiror-design-studio.com/joomla-extensions
# Technical Support:  Forum - http://www.vasiljevski.com/forum/index.php
# Version: 4.5.0
-------------------------------------------------------------------------*/
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$this->loadCSS($this->currPopupRoot . 'css/css_pirobox/style_1/style.css');
$this->loadCSS($this->currPopupRoot . 'css/css.css');
$this->loadCSS($this->currPopupRoot . 'css/Sansation/stylesheet.css');

// Load JavaScript files from current popup folder
$this->loadJS($this->currPopupRoot . 'js/jquery-ui-1.8.2.custom.min.ag.js');
$this->loadJS($this->currPopupRoot . 'js/pirobox_extended.ag.js');
$this->loadJS($this->currPopupRoot . 'js/piroboxInit.js');

// Load CSS from current popup folder
//$this->loadCSS($this->currPopupRoot . 'css/style.css');

// Set REL attribute needed for Popup engine
$this->popupEngine->rel = 'gallery';

// Set CLASS attribute needed for Popup engine
$this->popupEngine->className = 'pirobox_gall' . $this->getGalleryID();
?>
