<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.prettyPhoto.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/prettyPhoto.css');
$popup->rel = 'prettyPhoto'.$galleryCount.'[AdmirorGallery'.$galleryCount.']'.$articleID;
$popup->cssClass= '';
$popup->jsInclude='<script type="text/javascript" charset="utf-8">
        jQuery(document).ready(function(){
                jQuery("a[rel^='.$popup->rel.']").prettyPhoto();
        });
</script>';
$popup->initCode='jQuery("a[rel^='.$popup->rel.']").prettyPhoto();';
?>