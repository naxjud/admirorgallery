<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'shadowbox.js');
// Load CSS from current popup folder
$this->loadCSS($this->currPopupRoot.'shadowbox.css');

$this->popupEngine->rel = 'shadowbox[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->initCode='
<script type="text/javascript" charset="utf-8">
Shadowbox.init({
    modal: true,
	counterType: "default",
	slideshowDelay : 0
});
</script>';
?>