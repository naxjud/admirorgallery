<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/slimbox2.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/slimbox2.css');
$rel = 'lightbox[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= '';
$initCode =' 
jQuery("a[rel^=\'lightbox\']").slimbox({/* Put custom options here */}, null, function(el) {
return (this == el) || ((this.rel.length > 8) && (this.rel == el.rel));
		});';
?>