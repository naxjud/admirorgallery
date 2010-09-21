<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'jquery.easing-1.3.pack.js');
$this->loadJS($this->currPopupRoot.'sexylightbox.v2.3.jquery.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'sexylightbox.css');
$this->popupEngine->rel= 'sexylightbox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->endCode='
    <script type="text/javascript" charset="utf-8">
          jQuery(document).ready(function(){
          SexyLightbox.initialize({color:\'black\',dir:\''.$this->sitePath.'/plugins/content/AdmirorGallery/popups/'.$this->params['popupEngine'].'\'})
          });
</script>';
?>