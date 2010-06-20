<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/jquery.colorbox-min.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/colorbox.css');
$rel = 'colorbox[AdmirorGallery'.$galleryCount.''.$articleID.']';
$cssClass= '';
$doc->addScriptDeclaration('			jQuery(document).ready(function(){
				jQuery("a[rel='.$rel.']").colorbox({scalePhotos: true,maxWidth: 1000, maxHeight:600});
			});')
?>