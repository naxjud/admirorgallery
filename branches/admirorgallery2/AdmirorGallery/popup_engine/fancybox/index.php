<?php
defined('_JEXEC') or die('Restricted access');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.easing-1.3.pack.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.fancybox-1.3.1.pack.js');
$ag->addJavaScript('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.mousewheel-3.0.2.pack.js');
$ag->addCSS('/plugins/content/AdmirorGallery/popup_engine/'.$ag->params['popupEngine'].'/jquery.fancybox-1.3.1.css');
$popup->rel = 'fancybox[AdmirorGallery'.$galleryCount.']'.$articleID;
$popup->cssClass= '';
$popup->jsInclude='<script type="text/javascript" charset="utf-8">
        jQuery("a[rel='.$popup->rel.']").fancybox({
		 \'transitionIn\' : \'elastic\',
		 \'transitionOut\' : \'elastic\',
		 \'easingIn\' : \'easeOutBack\',
		 \'easingOut\' : \'easeInBack\'
        });
</script>'
?>