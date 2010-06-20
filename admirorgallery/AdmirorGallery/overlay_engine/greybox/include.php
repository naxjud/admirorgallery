<?php
	defined('_JEXEC') or die('Restricted access');
	global $mainframe;
	$js_root = $joomla_site_path.'/plugins/content/AdmirorGallery/overlay_engine/'.$_overlayEngine_.'/';
	$headtag = array();
	$headtag[] = '<script type="text/javascript">var GB_ROOT_DIR = "'. $js_root. '";</script>';
	$headtag[] = '<script type="text/javascript" src="'. $js_root. 'AJS.js"></script>';
	$headtag[] = '<script type="text/javascript" src="'. $js_root. 'AJS_fx.js"></script>';
	$headtag[] = '<script type="text/javascript" src="'. $js_root. 'gb_scripts.js"></script>';
	$headtag[] = '<link href="'. $js_root. 'gb_styles.css" rel="stylesheet" type="text/css" />';
	$mainframe->addCustomHeadTag(implode("\n", $headtag));

?>