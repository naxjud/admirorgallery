<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'jquery.prettyPhoto.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'prettyPhoto.css');
// Set REL attribute needed for Popup engine
$this->popupEngine->rel = 'prettyPhoto'.$this->index.'[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->endCode='<script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){
                jQuery("a[rel^='.$this->popupEngine->rel.']").prettyPhoto();
        });
</script>';
?>