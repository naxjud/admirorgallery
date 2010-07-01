<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/fancyzoom.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/fancyzoom.css');
$rel = 'fancyzoom[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= '';
$doc->addScriptDeclaration('jQuery(document).ready(function() {
    jQuery("a[rel='.$rel.']").fancyZoom({scaleImg: true, closeOnClick: true});
	jQuery(\'#zoom_close\').html(\'<img src="plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/images/closebox.png" alt="Close" style="border:none; margin:0; padding:0;" /> \');
});')
?>