<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/jquery.prettyPhoto.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/prettyPhoto.css');
$rel = 'prettyPhoto'.$galleryCount.'[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= '';
$jsInclude='<script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){
                jQuery("a[rel^='.$rel.']").prettyPhoto();
        });
</script>';
$initCode='jQuery("a[rel^='.$rel.']").prettyPhoto();';
?>