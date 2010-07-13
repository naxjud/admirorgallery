<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/*.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/top_up-min.js');
$popup->rel = 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->cssClass= 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$popup->customTag='toptions="group = ag'.$galleryCount.''.$articleID.', effect = clip, title = Gallery {alt} ({current} of {total}) , shaded = 1"';
$popup->jsInclude='<script type="text/javascript">
  TopUp.host = "'.$ag->sitePath.'/";
  TopUp.images_path = "plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/images/top_up/";
</script> ';
?>