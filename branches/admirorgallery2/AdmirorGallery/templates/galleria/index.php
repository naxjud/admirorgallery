<?php

// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadJS($AG->currTemplateRoot.'/galleria.js');
$AG->loadJS($AG->currTemplateRoot.'/themes/classic/galleria.classic.js');
$AG->loadCSS($AG->currTemplateRoot.'/galleria.css');

$AG->params['newImageTag']=false;

// Form HTML code
$html = '<div id="AG_'.$AG->getGalleryID().'" class="AG_'.$AG->params['template'].'">';

// Loops over the array of images inside target gallery folder, adding wrapper with SPAN tag and write Popup thumbs inside this wrapper
foreach ($AG->images as $imageKey => $imageName){
	$html.= $AG->writePopupThumb($imageName);
}

$html.='</div>';  

$html.='

<script>
    jQuery(\'#AG_'.$AG->getGalleryID().'\').galleria({
        image_crop: false,
	transition: \'fade\'
    });
</script>

<style type="text/css">
    #AG_'.$AG->getGalleryID().'{position:relative;background-color:#'.$AG->params['foregroundColor'].';}
    #AG_'.$AG->getGalleryID().' .galleria-thumbnails .galleria-image{border-color:#'.$AG->params['foregroundColor'].';}
    #AG_'.$AG->getGalleryID().' .galleria-thumb-nav-left:hover,
    #AG_'.$AG->getGalleryID().' .galleria-thumb-nav-right:hover,
    #AG_'.$AG->getGalleryID().' .galleria-info-link:hover,
    #AG_'.$AG->getGalleryID().' .galleria-info-close:hover{opacity:1;background-color:#'.$AG->params['highliteColor'].';}
</style>

';

?>