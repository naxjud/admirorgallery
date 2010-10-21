<?php


/** ensure this file is being included by a parent file */
defined( '_JEXEC' ) or die( 'Restricted access' );

// Load JavaScript files from current popup folder
$doc->addScript(JURI::root().'administrator/components/com_admirorgallery/fancybox/js/jquery.easing-1.3.pack.js');
$doc->addScript(JURI::root().'administrator/components/com_admirorgallery/fancybox/js/jquery.fancybox-1.3.1.pack.js');
$doc->addScript(JURI::root().'administrator/components/com_admirorgallery/fancybox/js/jquery.mousewheel-3.0.2.pack.js');

// Load CSS from current popup folder
$doc->addStyleSheet(JURI::root().'administrator/components/com_admirorgallery/fancybox/css/jquery.fancybox-1.3.1.css');

$ag_preview_content.='
<a href="'.substr(JURI::root(),0,-1).$ag_itemURL.'" title="'.$ag_itemURL.'" class="ag_imgThumb" rel="fancybox[\'AG\']" target="_blank">
     <img src="'.JURI::root().'administrator/components/com_admirorgallery/scripts/thumbnailer.php?img='.substr(JURI::root(),0,-1).$ag_itemURL.'&height=80" alt="'.$ag_itemURL.'" class="ag_imageThumb">
</a>
';	

// Insert JavaScript code needed to be loaded after gallery is formed
$ag_preview_content.='
<script type="text/javascript" charset="utf-8">
jQuery(function(){
        jQuery("a[rel=fancybox[\'AG\']]").fancybox({
		 \'transitionIn\' : \'elastic\',
		 \'transitionOut\' : \'elastic\',
		 \'easingIn\' : \'easeOutBack\',
		 \'easingOut\' : \'easeInBack\'
        });
});
</script>
';

?>