<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'jquery.colorbox-min.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'colorbox.css');
// Set REL attribute needed for Popup engine
$this->popupEngine->rel = 'colorbox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->endCode='
<script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function(){
	jQuery("a[rel='.$this->popupEngine->rel.']").colorbox({scalePhotos: true,maxWidth: 1000, maxHeight:600});
});
</script>';
?>