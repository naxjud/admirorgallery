<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');
$this->loadJS($this->currPopupRoot.'top_up-min.js');
//$this->loadJS($this->currPopupRoot.'jquery.colorbox-min.js');
//$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/*.js');
//$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/top_up-min.js');
$this->popupEngine->rel = 'top_up[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->className= 'top_up[AdmirorGallery'.$this->getGalleryID().']';
$this->popupEngine->customAttr='toptions="group = ag'.$this->getGalleryID().', effect = clip, title = Gallery {alt} ({current} of {total}) , shaded = 1"';
$this->popupEngine->endCode='<script type="text/javascript">
  TopUp.host = "'.$this->sitePath.'/";
  TopUp.images_path = "plugins/content/AdmirorGallery/popups/'.$this->params['popupEngine'].'/images/top_up/";
</script> ';
?>