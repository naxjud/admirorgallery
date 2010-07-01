<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/*.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/top_up-min.js');
$rel = 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$customTag='toptions="group = ag'.$galleryCount.''.$articleID.', effect = clip, title = Gallery {alt} ({current} of {total}) , shaded = 1"';
$jsInclude='<script type="text/javascript">
  TopUp.host = "'.$joomla_site_path.'/";
  TopUp.images_path = "plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/images/top_up/";
</script> ';
?>