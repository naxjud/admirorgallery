<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/*.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/top_up-min.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/sexylightbox.css');
$rel = 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= 'top_up[AdmirorGallery'.$galleryCount.''.$articleID.']';
$customTag='toptions="group = ag'.$galleryCount.''.$articleID.', effect = clip, title = Gallery {alt} ({current} of {total}) , shaded = 1"';
$jsInclude='<script type="text/javascript">
  TopUp.host = "'.$joomla_site_path.'/";
  TopUp.images_path = "plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/images/top_up/";
</script> ';
?>