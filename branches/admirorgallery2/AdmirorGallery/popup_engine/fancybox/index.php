<?php
defined('_JEXEC') or die('Restricted access');
$AG->loadJS('popup_engine/'.$AG->params['popupEngine'].'/jquery.easing-1.3.pack.js');
$AG->loadJS('popup_engine/'.$AG->params['popupEngine'].'/jquery.fancybox-1.3.1.pack.js');
$AG->loadJS('popup_engine/'.$AG->params['popupEngine'].'/jquery.mousewheel-3.0.2.pack.js');
$AG->loadCSS('popup_engine/'.$AG->params['popupEngine'].'/jquery.fancybox-1.3.1.css');
$AG->popupEngine->rel = 'fancybox[AdmirorGallery'.$AG->getGalleryID().']';
$AG->popupEngine->cssClass= '';
$AG->popupEngine->jsInclude='<script type="text/javascript" charset="utf-8">
        jQuery("a[rel='.$AG->popupEngine->rel.']").fancybox({
		 \'transitionIn\' : \'elastic\',
		 \'transitionOut\' : \'elastic\',
		 \'easingIn\' : \'easeOutBack\',
		 \'easingOut\' : \'easeInBack\'
        });
</script>'
?>