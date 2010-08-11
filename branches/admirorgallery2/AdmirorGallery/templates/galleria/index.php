<?php
// Joomla security code
defined('_JEXEC') or die('Restricted access');

$AG->loadCSS($AG->currTemplateRoot.'galleria.css');
$AG->loadJS($AG->currTemplateRoot.'galleria.js');
$AG->loadJS($AG->currTemplateRoot.'themes/classic/galleria.classic.js');
//$html=$AG->initPopup();

$AG->params['newImageTag']= false;
// Form HTML code
$html = '<div id="AG_'.$AG->getGalleryID().'" style="width:'.$AG->params['frame_width'].'px; height:'.$AG->params['frame_height'].'px;">';
foreach ($AG->images as $imageKey => $imageName){
    $html.= $AG->writeImage($imageName);
}
$html.='</div>    

<script>
    jQuery(\'#AG_'.$AG->getGalleryID().'\').galleria({
        image_crop: false,
        transition: \'fade\',
		max_scale_ratio: 1
    });
</script>
<style type="text/css">
.galleria-container{position:relative;overflow:hidden;background:#fff;}
</style>'
?>