<?php
defined('_JEXEC') or die('Restricted access');
$this->popupEngine->js[] = 'popup_engine/'.$this->params['popupEngine'].'/js/pirobox.js';
$this->popupEngine->js[] = 'popup_engine/'.$this->params['popupEngine'].'/js/piroboxInit.js';
$this->popupEngine->css[] = 'popup_engine/'.$this->params['popupEngine'].'/css/style.css';
$this->popupEngine->rel = 'pirobox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->cssClass= 'pirobox_AdmirorGallery'.$this->getGalleryID();
//include_once('plugins/content/AdmirorGallery/popup_engine/'.$this->params['popupEngine'].'/include.php');
?>
