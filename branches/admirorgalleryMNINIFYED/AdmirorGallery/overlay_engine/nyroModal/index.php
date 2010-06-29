<?php
defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/js/jquery.nyroModal-1.6.2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/styles/nyroModal.css');
$rel = 'gal'.$galleryCount.'[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= 'nyroModal';
$jsInclude='<script type="text/javascript" charset="utf-8">
        $(\'.nyroModal a\').nyroModal();
        });
		
</script>'
?>