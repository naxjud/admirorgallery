<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/shadowbox.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/shadowbox.css');
$rel = 'shadowbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= '';
$doc->addScriptDeclaration('Shadowbox.init({
    modal: true,
	counterType: "default",
	slideshowDelay : 0
});')
?>