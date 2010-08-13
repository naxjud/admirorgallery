<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'fancyzoom.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'fancyzoom.css');
$this->popupEngine->rel = 'fancyzoom[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->endCode='
    <script type="text/javascript" charset="utf-8">
    jQuery(document).ready(function() {
    jQuery("a[rel='.$this->popupEngine->rel.']").fancyZoom({scaleImg: true, closeOnClick: true});
	jQuery(\'#zoom_close\').html(\'<img src="plugins/content/AdmirorGallery/popups/'.$this->params['popupEngine'].'/images/closebox.png" alt="Close" style="border:none; margin:0; padding:0;" /> \');
});
</script>';
?>