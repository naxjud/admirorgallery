<?php
		defined('_JEXEC') or die('Restricted access');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/jquery.easing-1.3.pack.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/jquery.fancybox-1.3.1.pack.js');
$doc->addScript($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/jquery.mousewheel-3.0.2.pack.js');
$doc->addStyleSheet($joomla_site_path.'/plugins/content/AdmirorGallery/popup_engine/'.$_popupEngine_.'/jquery.fancybox-1.3.1.css');
$rel = 'fancybox[AdmirorGallery'.$galleryCount.']'.$articleID;
$cssClass= '';
$jsInclude='<script type="text/javascript" charset="utf-8">
        jQuery("a[rel=fancybox[AdmirorGallery'.$galleryCount.']'.$articleID.']").fancybox({
		 \'transitionIn\' : \'elastic\',
		 \'transitionOut\' : \'elastic\',
		 \'easingIn\' : \'easeOutBack\',
		 \'easingOut\' : \'easeInBack\'
        });
</script>'
?>