<?php
defined('_JEXEC') or die('Restricted access');

$this->loadJS($this->currPopupRoot.'js/slimbox2.js');
$this->loadCSS($this->currPopupRoot.'css/slimbox2.css');
$this->popupEngine->rel = 'lightbox[AdmirorGallery'.$this->getGalleryID().']';

// $this->popupEngine->className = 'thumb'.$this->getGalleryID();

// CUSTOM SCRIPTS WHICH LOADS BEFORE GALLERY RENDERING
// $this->popupEngine->initCode='Ovo ide ispred galerije<br />';

// CUSTOM IMAGE TAG TEST
// $this->popupEngine->customPopupThumb='
// <a href="{imagePath}" title="{imageDescription}" class="{className}" rel="{rel}" {customAttr} target="_blank">
// {newImageTag}
// <img src="{thumbImagePath}" alt="{imageDescription}" class="ag_imageThumb">
// </a>
// ';

// CUSTOM SCRIPTS WHICH LOADS AFTER GALLERY RENDERING
// $this->popupEngine->endCode='
// <script type="text/javascript">
// jQuery("#AG_'.$this->getGalleryID().' .'.$this->popupEngine->className.'").css({
//   border:"2px dashed blue",
//   display:"block"
// });
// </script>
// Ova galerija koristi Slimbox Popup koji je napravio neki Svetskimegacar.
// <p>&nbsp;</p>
// <p>&nbsp;</p>
// ';

?>