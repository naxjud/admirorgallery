<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

// Load jQuery Flash library from current template folder
$AG->loadJS($AG->currTemplateRoot.'jquery.swfobject.1-1-1.min.js');

// Reset $html variable from previous entery
$html='';

// Generate XML string needed for Flash gallery
$xmlGen='';
$xmlGen.='<?xml version="1.0" encoding="utf-8"?>';
$xmlGen.='<photos>';
foreach ($AG->images as $imageKey => $imageName)
{
	$AG->getImageInfo($imageName);
	$xmlGen.='<photo url="'.$AG->imagesFolderPath.$imageName.'" desc="'.$AG->writeDescription($imageName).'" width="'.$AG->imageInfo["width"].'" height="'.$AG->imageInfo["height"].'" />';
}
$xmlGen.='</photos>';

// Insert JavaScript code needed for jQuery Flash library
$AG->insertJSCode('
jQuery(function(){
	jQuery("#AG_'.$AG->getGalleryID().'").flash({
		swf: "'.$AG->pluginPath.$AG->currTemplateRoot.'simple_flash_gallery.swf",
		width: '.$AG->params['frame_width'].',
		height: '.$AG->params['frame_height'].',
		flashvars: {
			xmlString:\''.$xmlGen.'\',
			thumbPercentage:'.$AG->getParameter("thumbPercentage",30).'
		}
	});
});
');

// Add wrapper with unique ID name,used by jQuery Flash library for embeding SWF file
$html.='
<div class="ag_reseter" id="AG_'.$AG->getGalleryID().'"></div>
';

?>
